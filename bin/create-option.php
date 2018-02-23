<?php
declare(strict_types=1);

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$class = $argv[1];

if(!class_exists($class)) {
    $file = array_pop(explode('\\', $class));
    $path = getcwd() . DIRECTORY_SEPARATOR . $file . '.php';
    if(file_exists($path)) {
        require $path;
    }
    if(!class_exists($class)) {
        echo " Class {$class} does not exist or cannot be found.\n";
        exit(1);
    }
}

$reflection = new ReflectionClass($class);

if($reflection->inNamespace()) {
    $classDir = dirname($reflection->getFileName());
    $namespace = explode('\\', $reflection->getName());
    $className = $namespace[count($namespace) - 1];
    $optionDir = $classDir . DIRECTORY_SEPARATOR . $className;

    if(!file_exists($optionDir)) {
        mkdir($optionDir);
    }

    $optionDir .= DIRECTORY_SEPARATOR . 'Option';
    if(!file_exists($optionDir)) {
        mkdir($optionDir);
    }
    $namespace = $reflection->getName();
} else {
    $optionDir = getcwd() . DIRECTORY_SEPARATOR . $class;
    if(!file_exists($optionDir)) {
        mkdir($optionDir);
    }
    $optionDir .= DIRECTORY_SEPARATOR . 'Option';
    if(!file_exists($optionDir)) {
        mkdir($optionDir);
    }
    $namespace = $class;
}

$optionTemplate = <<<gfrfgrfg
<?php
declare(strict_types=1);

namespace %%NAMESPACE%%\Option;

abstract class Option extends \maarky\Option\Type\Object\Option
{
    public static function validate(\$value): bool
    {
        return parent::validate(\$value) && \$value instanceof %%CLASS%%;
    }
}
gfrfgrfg;

$someTemplate = <<<gfrfgrfg
<?php
declare(strict_types=1);

namespace %%NAMESPACE%%\Option;

use maarky\Option\Component\BaseSome;

class Some extends Option
{
    use BaseSome;

    public function get(): %%CLASS%%
    {
        return \$this->value;
    }
}
gfrfgrfg;

$noneTemplate = <<<gfrfgrfg
<?php
declare(strict_types=1);

namespace %%NAMESPACE%%\Option;

use maarky\Option\Component\BaseNone;

class None extends Option
{
    use BaseNone;

    public function get(): %%CLASS%%
    {
        return \$this;
    }

    public function getOrElse(\$else): %%CLASS%%
    {
        return \$else;
    }

    public function getOrCall(callable \$call): %%CLASS%%
    {
        return \$call();
    }
}
gfrfgrfg;

$find = ['%%NAMESPACE%%', '%%CLASS%%'];
$replace = [$namespace, '\\' . ltrim($class, '\\')];

$optionContent = str_replace($find, $replace, $optionTemplate);
$someContent = str_replace($find, $replace, $someTemplate);
$noneContent = str_replace($find, $replace, $noneTemplate);

$success = file_put_contents($optionDir . DIRECTORY_SEPARATOR . 'Option.php', $optionContent);
if(!$success) {
    echo "Could not create " . $optionDir . DIRECTORY_SEPARATOR . "Option.php\n";
    exit(1);
}
echo "Created " . $optionDir . DIRECTORY_SEPARATOR . "Option.php\n";
$success = file_put_contents($optionDir . DIRECTORY_SEPARATOR . 'Some.php', $someContent);
if(!$success) {
    echo "Could not create " . $optionDir . DIRECTORY_SEPARATOR . "Some.php\n";
    exit(1);
}
echo "Created " . $optionDir . DIRECTORY_SEPARATOR . "Some.php\n";
$success = file_put_contents($optionDir . DIRECTORY_SEPARATOR . 'None.php', $noneContent);
if(!$success) {
    echo "Could not create " . $optionDir . DIRECTORY_SEPARATOR . "None.php\n";
    exit(1);
}
echo "Created " . $optionDir . DIRECTORY_SEPARATOR . "None.php\n";