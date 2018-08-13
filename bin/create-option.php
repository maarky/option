<?php
declare(strict_types=1);

$autoload = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php',

];
$autoloaded = false;
foreach ($autoload as $file) {
    if(file_exists($file)) {
        require $file;
        $autoloaded = true;
    }
}
if(!$autoloaded) {
    echo "Could not require autoload file.\n";
    exit(1);
}

$args = getopt('c', ['in:', 'out:', 'src::', 'srcn::', 'help']);
echo "\n";

if(array_key_exists('help', $args)) {
    echo <<<jfhkjhkd
-c Only process classes, not interfaces
--in Namespace you want to create Options for (Required)
--out Namespace root for generated Options (Required)
--src The source root (defaults to cwd)
--srcn The namespace for the source root. Only needed if the root namespace does not have a directory in --src
       For example, if --in="org_name\project_name\Foo" and --srcn="org_name\project_name" it expects Foo to be a directory in --src
       If --srcn is not specified it expects to fine the directory org_name in --src.

jfhkjhkd;
exit(0);
}

$in = trim($args['in'], '\\');
$out = trim($args['out'], '\\');

$inArr = explode('\\', $in);
$outArr = explode('\\', $out);
for($x = 0; $x < count($inArr); $x++) {
    if($inArr[$x] != $outArr[$x]) {
        break;
    }
}

$inRemovePrefix = implode('\\', array_slice($inArr, 0, $x));

if(0 === $x) {
    echo "in and out must begin with the same namespaces.\n";
    exit(1);
}

$srcn = array_key_exists('srcn', $args) && !empty($args['srcn']) ? trim($args['srcn'], '\\') : null;

if($args['src'][0] == '/') {
    $src = $args['src'];
} elseif(array_key_exists('src', $args) && !empty($args['src'])) {
    $src = __DIR__ . '/' . $args['src'];
} else {
    $src = getcwd();
}
if(!file_exists($src)) {
    echo "src does not exist.\n";
    exit(1);
}
if(!is_null($srcn)) {
    if(0 !== strpos($in, $srcn . '\\') || 0 !== strpos($out, $srcn . '\\')) {
        echo "srcn must match the beginning of in and out\n";
        echo "In otherwords, if srcn begins with my\project then so must in and out\n";
        exit(1);
    }
    $searchSrc = $src . '/' . str_replace('\\', '/', substr($in, strlen($srcn) + 1));
    $outSrc = $src . '/' . str_replace('\\', '/', substr($out, strlen($srcn) + 1));
} else {
    $searchSrc = $src . '/' . str_replace('\\', '/', $in);
    $outSrc = $src;
}

if(!file_exists($searchSrc)) {
    echo "$searchSrc does not exist.\n";
    exit(1);
}
$classesOnly = array_key_exists('c', $args) ?: false;


$declaredClasses = [];

$allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($searchSrc));
$phpFiles = new RegexIterator($allFiles, '/\.php$/');
foreach ($phpFiles as $phpFile) {
    $content = file_get_contents($phpFile->getRealPath());
    $tokens = token_get_all($content);
    $namespace = '';
    for ($index = 0; isset($tokens[$index]); $index++) {
        if (!isset($tokens[$index][0])) {
            continue;
        }
        if (T_NAMESPACE === $tokens[$index][0]) {
            $index += 2; // Skip namespace keyword and whitespace
            while (isset($tokens[$index]) && is_array($tokens[$index])) {
                $namespace .= $tokens[$index++][1];
            }
        }
        if (((T_INTERFACE === $tokens[$index][0] && !$classesOnly) || T_CLASS === $tokens[$index][0]) && T_WHITESPACE === $tokens[$index + 1][0] && T_STRING === $tokens[$index + 2][0]) {
            $index += 2; // Skip class keyword and whitespace
            $name = $namespace.'\\'.$tokens[$index][1];
            $outNamespace = $out . substr($name, strlen($inRemovePrefix));
            $declaredClasses[$name] = [
                'out' => $outNamespace,
                'class' => new ReflectionClass($name)
            ];

            # break if you have one class per file (psr-4 compliant)
            # otherwise you'll need to handle class constants (Foo::class)
            //break;
        }
    }
}

uasort($declaredClasses, function($left, $right) use($in) {
    $countParents = function (ReflectionClass $class) use($in) {
        $parent = $class->getParentClass();
        $parents = 0;
        while($parent) {
            if(0 === strpos($parent->getName(), $in)) {
                $parents++;
                $parent = $parent->getParentClass();
            } else {
                $parent = false;
            }
        }
        return $parents;
    };
    return $countParents($left['class']) <=> $countParents($right['class']);
});


$templateOption = <<<fbggrbhgrbh
<?php
declare(strict_types=1);

namespace %%namespace%%;

abstract class Option extends %%parent%%
{
    public static function isValid(\$value): bool
    {
        return \$value instanceof \%%class%% && parent::isValid(\$value);
    }
}
fbggrbhgrbh;

$templateSome = <<<fbggrbhgrbh
<?php
declare(strict_types=1);

namespace %%namespace%%;

use maarky\Option\Component\BaseSome;

class Some extends Option
{
    use BaseSome;
}
fbggrbhgrbh;

$templateNone = <<<fbggrbhgrbh
<?php
declare(strict_types=1);

namespace %%namespace%%;

use maarky\Option\Component\BaseNone;

class None extends Option
{
    use BaseNone;
}
fbggrbhgrbh;

foreach ($declaredClasses as $class) {
    $outTemp = ltrim($srcn ? str_replace($srcn, '', $class['out']) : $class['out'], '\\');
    $namespaceParts = explode('\\', $outTemp);
    if($srcn) {
        array_shift($namespaceParts);
    }
    $dir = $srcn ? str_replace($inRemovePrefix . '\\', '', $outSrc) : $outSrc;
    if(!file_exists($dir)) {
        mkdir($dir);
    }
    while (count($namespaceParts)) {
        $dir .= '/' . array_shift($namespaceParts);
        if(!file_exists($dir)) {
            mkdir($dir);
        }
    }
    $parentClass = $class['class']->getParentClass();

    if(!$parentClass || 0 !== strpos($parentClass->getName(), $in) || !array_key_exists($parentClass->getName(), $declaredClasses)) {
        $parent = '\maarky\Option\Type\Object\Option';
    } else {
        $parent = $declaredClasses[$parentClass->getName()]['out'];
    }

    $option = str_replace(['%%namespace%%', '%%class%%', '%%parent%%'], [$class['out'], $class['class']->getName(), $parent], $templateOption);
    $optionPath = $dir . '/Option.php';
    $some = str_replace('%%namespace%%', $class['out'], $templateSome);
    $somePath = $dir . '/Some.php';
    $none = str_replace('%%namespace%%', $class['out'], $templateNone);
    $nonePath = $dir . '/None.php';

    file_put_contents($optionPath, $option);
    file_put_contents($somePath, $some);
    file_put_contents($nonePath, $none);

    echo "Created {$class['class']->getName()}\n";
}