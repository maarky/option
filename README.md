PHP Options
===========

Options are a type of object used when a value may or may not exist, like as a return value for a function that may or 
may not have a value to return. For example, when you call a find() method on a repository object it may or may not find 
the entity you are looking for.

You can also use Options when assigning a value to an optional property. For example, a User object might have an ID.
When pulling the user from a database you have an ID so you supply it to the object as a Some but when you create a new
User object you do not yet have an ID so you provide a None. You can easily determine whether or not a User object has
been persisted by calling $user->getId()->isDefined() without the User object needing to understand its origins or
whether or not it is persisted. Another advantage to this approach is that since you may or may not have an ID getting 
an Option back forces you to acknowledge this fact and deal with the case where no ID is available.

This implementation of Options also allows you to specify what type of data it contains. It comes with a base Option that
can accept any type of data as well as Option classes for each PHP data type and for DateTime objects. It can be extended
to create custom Options for any other type of data you require whether it be a specific class or an array that meets
specific criteria.

About Monads
============

This library uses my SingleContainer interface for creating monads. 

Requirements
============

This library requires PHP 7. Since many people may not yet be working with PHP 7 this library includes a Vagrantfile with
provisioning that installs php7.0-cli and Xdebug. It will also install Composer and PHPUnit. You can use this to run the 
unit tests and as a sandbox.

Installation
============

To install simply use composer:

    composer require maarky/option

Documentation
=============

Basics
------

This implementation of Options consist of three parts:

1.  An abstract Option class (marrky\Option\Option)
1.  A None class extending Option (marrky\Option\None) and mixing in the base None trait (maarky\Option\Component\BaseNone)
1.  A Some class extending Option (maarky\Option\Some) and mixing in the base Some trait (maarky\Option\Component\BaseSome)

These classes are extended by 10 additional Option types, one for each PHP data type:

* Arr for arrays (marrky\Option\Type\Arr\Option)
* Bool for booleans (marrky\Option\Type\Bool\Option)
* Callback for callbacks (marrky\Option\Type\Callback\Option)
* Float for floats (marrky\Option\Type\Float\Option)
* Int for integers (marrky\Option\Type\Int\Option)
* Null for null values (marrky\Option\Type\Null\Option)
* Object for any type of objects (marrky\Option\Type\Object\Option)
* Resource for resources (marrky\Option\Type\Resource\Option)
* String for strings (marrky\Option\Type\String\Option)

Each of these extend the base Option class and are extended by their own Some and None classes.

The Option class should be used for type declarations while the Some and None classes provide the actual objects you will
be working with. Consider the following example:

    use marrky\Option;
    use marrky\Option\None;
    use marrky\Option\Some;
     
    ...
     
    public function find(int $id): Option
    {
        $result = $this->db->execute("some SQL");
        if(!$result->rowCount()) {
            return new None;
        }
        $entity = new Entity($result->fetch());
        return new Some($entity);
    }

In this example you specify that your function returns an Option and PHP will require a return value of Some or None. You
know that a Some can be mapped or filtered and the value can be retrieved. You know that a None can be replaced with an
alternate value and that a default value can be retrieved. Most importantly, you know that you can safely do all of
these things without even knowing if you have a Some or a None.

Creating Options
----------------

You create an Option by using the static new() method on the abstract Option class. The Option class has an abstract 
validate() method that determines whether or not a given value can be stored in a Some. If you try to create an Option 
with an invalid value you get a None back.

    use marrky\Option\Option;
    use marrky\Option\Type\Int\Option as IntOption;
    use marrky\Option\Type\Null\Option as NullOption;
     
    Option::new(1);// returns Some
    Option::new('1');// returns Some
    Option::new(null);// returns None
     
    IntOption::new(1);// returns Some
    IntOption::new('1');// returns None
    IntOption::new(1.0);// returns None
    IntOption::new(null);// returns None
    IntOption::new('');// returns None
     
    NullOption::new(null);// returns Some
    NullOption::new('');// returns None
    

Methods For Determining Option Type
-----------------------------------

When you receive an Option you may need to know whether you got a Some or a None.

### isDefined()

Some will always return TRUE.  
None will always return FALSE.

### isEmpty()

Some will always return FALSE.  
None will always return TRUE.

Methods For Retrieving Option Values
------------------------------------

### get()

Some will always return the stored value.  
None will always throw a type error.

    Option::new(5)->get(); //returns 5
    Option::new(null)->get(); //throws type error

### getOrElse($else)

Some will always return the stored value.  
None will return $else or throw a type error is $else is not a valid value.

    Option::new(5)->getOrElse(7); //returns 5
    Option::new(null)->getOrElse(7); //returns 7
    IntOption::new(null)->getOrElse('7'); //throws type error

### getOrCall(callable $call)

Some will always return the stored value.  
None will always call $call and return it or throw a type error is $call returns an invalid value.

    Option::new(5)->getOrCall(function() { return 7; }); //returns 5
    Option::new(null)->getOrCall(function() { return 7; }); //returns 7
    IntOption::new(null)->getOrCall(function() { return '7'; }); //throws type error

Replacing An Option
-------------------

### orElse(Option $else)

Some will always return itself.  
None will always return $else if $else is a Single Container, otherwise throws a type error.

    Option::new(5)->orElse(Option::new(7)); //returns Some(5)
    Option::new(null)->orElse(Option::new(7)); //returns Some(7)
    Option::new(null)->orElse(7); //throws type error

### orCall(callable $else)

Some will always return itself.  
None will always call $else and return its return value if it is a Single Container, otherwise throws a type error. 

    Option::new(5)->orCall(function() { return Option::new(7); }); //returns Some(5)
    Option::new(5)->orCall(function() { return 7; }); //returns Some(5) because the function is ignored by Some
                                                      //on a None this would throw type error
     
    Option::new(null)->orCall(function() { return Option::new(7); }); //returns Some(7)
    Option::new(null)->orCall(function() { return 7; }); //throws type error
     
    IntOption::new(null)->orCall(function() { return IntOption::new(7); }); // returns Some(7)
    IntOption::new(null)->orCall(function() { return 7; }); //throws type error
    IntOption::new(null)->orCall(function() { return StringOption::new(null); }); // returns String\None

Filtering An Option
-------------------

### filter(callable $filter)

$filter must be a callable that takes the option value and returns a boolean.

Some will return itself if the callback returns TRUE, otherwise it returns a None. It will return the same type of none 
as itself. In other words a String Some will return a String None if the callback returns FALSE.  
None always returns itself.

    Option::new(5)->filter(function(int $value) { return $value > 1; }); //returns Some(5)
    Option::new(5)->filter(function(int $value) { return $value > 5; }); //returns None
     
    Option::new(null)->filter(function(int $value) { return $value > 1; }); //returns None
    Option::new(null)->filter(function(int $value) { return $value > 5; }); //returns None
    
### filterNot(callable $filter)

filterNot() is the inverse of the filter() method.
 
$filter must be a callable that takes the option value and returns a boolean.

Some will return itself if the callback returns FALSE, otherwise it returns a None. It will return the same type of none 
as itself. In other words a String Some will return a String None if the callback returns TRUE.  
None always returns itself.

    Option::new(5)->filter(function(int $value) { return $value <= 5; }); //returns None
    Option::new(5)->filter(function(int $value) { return $value > 5; }); //returns Some(5)
     
    Option::new(null)->filter(function(int $value) { return $value > 1; }); //returns None
    Option::new(null)->filter(function(int $value) { return $value > 5; }); //returns None

Mapping an Option
-----------------

### map(callback $map)

$map takes the Option value and returns any value. This value is returned wrapped in an Option. If the callback returns
an Option it will be wrapped in a Some. If the callback does not return an Option it will return a Some of the same type
if possible, otherwise a base Some.

Some will return an Option containing the return value of the callback.  
None will always return itself.

    Option::new(5)->map(function(int $value) { return $value * 2; }); //returns Some(10)
    Option::new(5)->map(function(int $value) { return (string) $value; }); //returns Some('5')
    Option::new(5)->map(function(int $value) { return Option::new($value); }); //returns Some(Some(5))
    Option::new(5)->map(function(int $value) { return Option::new(null); }); //returns Some(None)
     
    IntOption::new(5)->map(function(int $value) { return $value * 2; }); //returns Int\Some(10)
    IntOption::new(5)->map(function(int $value) { return (string) $value; }); //returns Option\Some('5')
     
    Option::new(null)->map(function(int $value) { return $value * 2; }); //returns None
    Option::new(null)->map(function(int $value) { return (string) $value; }); //returns None
    Option::new(null)->map(function(int $value) { return new Some($value); }); //returns None
    Option::new(null)->map(function(int $value) { return new None; }); //returns None

### flatMap(callback $map)

This works in much the same way as map() except the callback must return a Single Container. A type error
is thrown if it doesn't.

    Option::new(5)->flatMap(function(int $value) { return $value * 2; }); ////throws an UnexpectedValueException exception
    Option::new(5)->flatMap(function(int $value) { return new Some((string) $value); }); //returns Some('5')
    Option::new(5)->flatMap(function(int $value) { return new None; }); //returns None
     
    IntOption::new(5)->flatMap(function(int $value) { return $value * 2; }); ////throws type error
    IntOption::new(5)->flatMap(function(int $value) { return Option::new((string) $value); }); //returns Option\Some('5')
     
    Option::new(null)->flatMap(function(int $value) { return $value * 2; }); //returns None
    Option::new(null)->flatMap(function(int $value) { return (string) $value; }); //returns None
    Option::new(null)->flatMap(function(int $value) { return new Some($value); }); //returns None
    Option::new(null)->flatMap(function(int $value) { return new None; }); //returns None

Performing An Operation Using An Options Value
----------------------------------------------

### foreach(callback $each): Option

This method simply calls the $each function passing in the Options value. It returns $this.

Some calls the $each function passing in the Options value.  
None ignores the callback and does nothing.

    Option::new('Hello')->foreach(function(string $value) { echo $value; }); //echoes 'Hello'.
    Option::new('/path/to/file')->foreach(function(string $value) { unlink($value); }); //deletes a file.
    Option::new(null)->foreach(function(string $value) { echo $value; }); //does nothing.

Performing An Operation Using No Value
--------------------------------------

### fornothing(callback $nothing): Option

This is the inverse of foreach() and only operates on a None. This method simply calls the $nothing function passing in 
the Option. It returns $this.

Some ignores the callback and does nothing  
None calls the $nothing function passing in the Option.

    Option::new('Hello')->fornothing(function(string $value) { echo $value; }); //does nothing
    Option::new(null)->foreach(function(Option $option) { echo get_class($option); }); //echoes "marrky\Option\None".

Testing Equality Between Two Options
------------------------------------

### equals(option $option)

The equals method checks if the two Options are the same type and if their values are equal and returns a Boolean.

Some returns TRUE if the provided Option is the same type and contains the same value, otherwise FALSE;  
None returns TRUE if the provided Option is the same type of None, otherwise FALSE.

    Option::new(1)->equals(Option::new(1)); //returns TRUE
    Option::new(1)->equals(Option::new('1')); //returns FALSE
    Option::new(1)->equals(Option::new(2)); //returns FALSE
    Option::new(1)->equals(Option::new(null)); //returns FALSE
     
    IntOption::new(1)->equals(Option::new(1)); // returns false
     
    Option::new(null)->equals(Option::new(null)); // returns TRUE
    Option::new(null)->equals(IntOption::new(null)); // returns FALSE
    Option::new(null)->equals(Option::new(1)); // returns FALSE

Creating Custom Options
=======================

Creating custom options is simple. All you have to do is create an Option class that extends the base Option class or
another appropriate Option class. Give it a validate($value) method that checks that the value is of the right type and
returns a boolean. Then create a new Some class and None class in the same namespace as your Option class, have them
extend your new Option class and mix in the BaseSome or BaseNone traits.

Entity Option Example
---------------------

Suppose you have a User object and a User repository where you get your User Objects. You can create a custom User
Option and have your find() method return an option of this type. Now you not only get all the benefits of an Option but
you also know that if the method returns a Some it will contain a User.

User/Option/Option.php
    
    namespace User\Option;
     
    use User;
     
    class Option extends \marrky\Option\Type\Object\Option
    {
        public static function validate($value): bool
        {
            return parent::validate($value) && $value istanceof User;
        }
    }

User/Option/Some.php

    namespace User\Option;
     
    class Some extends Option
    {
        use maarky\Option\Component\BaseSome;
    }

User/Option/None.php

    namespace User\Option;
     
    class None extends Option
    {
        use maarky\Option\Component\BaseNone;
    }

Notice that the validate() method calls its parent. This may not be strictly necessary here but it's a good idea to make
sure that your Option value is valid with its parent.

You would use this Option in your repository class in the same way as we did in the example above in the Basics section.

    use User\Option\Option;
     
    ...
     
    public function find(int $id): Option
    {
        $result = $this->db->execute("some SQL");
        $entity = $result->rowCount() ? new User($result->fetch()) : null;
        
        return Option::new($entity);
    }

Array Option Example
--------------------

You might also be working with an array and you want to be sure you get the type of array you want. For example:

Point/Option/Option.php
    
    namespace Point\Option;
     
    class Option extends \marrky\Option\Type\Arr\Option
    {
        public static function validate($value): bool
        {
            return parent::validate($value) && 2 == count($value) && isset($value['lat']) && isset($value['lon'])
                   && is_float($value['lat']) && is_float($value['lon']);
        }
    }

Point/Option/Some.php

    namespace Point\Option;
     
    class Some extends Option
    {
        use maarky\Option\Component\BaseSome;
    }

Point/Option/None.php

    namespace Point\Option;
     
    class None extends Option
    {
        use maarky\Option\Component\BaseNone;
    }

And this is how you would use it:

    $point = new Point\Option::new(['lat' => 123.456, 'lon' => 12.34]); //returns Point\Option\Some
    $point->filter(function(array $point) { return $point['lat'] > 123}); // returns the Point\Option\Some
    $point->filter(function(array $point) { return $point['lat'] < 123}); // returns Point\Option\None
    Point\Option::new(['lat' => 123.456, 'lon' => 12.34, 'elevation' => 12345]); //returns Point\Option\None

Creating Custom Options With Command Line Tool
----------------------------------------------

A simple way to create a custom Option class that accommodates a specific class as its value is to use the command 
line. The Option library comes with a create-option command installed inside the bin directory. Composer will copy 
it to the composer vendor/bin directory. The way you use it is to simply provide the name of a class you want to
create a custom Option for.

If the class can be found using the Composer autoloader it will create a directory in the same directory as the class 
with the same name as the class, then a subdirectory named Option which will contain three class files for Option, 
Some, and None. For example:

    ubuntu@ubuntu-xenial:/vagrant/sandbox$ ../bin/create-option "maarky\Option\Type\Arr\Some"
    Created /vagrant/src/maarky/Option/Type/Arr/Some/Option/Option.php
    Created /vagrant/src/maarky/Option/Type/Arr/Some/Option/Some.php
    Created /vagrant/src/maarky/Option/Type/Arr/Some/Option/None.php

If it was unable to load the class with the Composer autoloader it will look for a file in the current working
directory with the same name as the class. In the above example it would look in the current directory for a 
file named Some.php.

If the class is not in a namespace, for example if you give it a built in PHP class, it will create it inside the 
current working directory. For example:

    ubuntu@ubuntu-xenial:/vagrant/sandbox$ ../bin/create-option DateTime
    Created /vagrant/sandbox/DateTime/Option/Option.php
    Created /vagrant/sandbox/DateTime/Option/Some.php
    Created /vagrant/sandbox/DateTime/Option/None.php

Overriding Equals
-----------------

You may need to override the equals() method in the Some class. The None class is probably fine using the default since
it returns TRUE only when comparing it to another None of the same type. However, the Some method might need some
customization.

The base Some trait returns TRUE when comparing it to another Some of the same type with the same value (using ===). But
if you are comparing two length objects, one representing one foot and the other 12 inches, the default equals() method
will return false since the two length objects are different. However, you might want these to evaluate as equal since
they both represent the same length. Also, you might not care if the Option types are the same so long as their values
are equal.

    public function equals(Option $option): bool
    {
        if($option->isEmpty()) {
            return false;
        }
        if(!$option->get() instanceof Length) {
            return false;
        }
        return $this->value->equals($option->get());
    }

Usage Examples
==============

Filtering
---------

Suppose you have a User repository with a findByEmail() method and a findByLogin() method. It is simple for one method 
to use the other with a filter.

    class UserRepository
    {
        public function findByEmail(string $email): Option
        {
            $result = $this->db->execute('SELECT * FROM users WHERE email = ?', [$email], [\PDO::PARAM_STR]);
            $user = $result->rowCount() ? new User($result->fetchRow()) : null;
            return Option::new($user);
        }
         
        pubic function findByLogin(string $email, string $password): Option
        {
            return $this->findByEmail($email)
                        ->filter(function(User $user) use($password) {
                            return password_verify($password, $user->getPasswordHash());
                        });
        }
    }

In this example findByEmail() does the work of locating a user. If we follow the happy path and the login is correct, 
then when findByLogin() calls findByEmail() it will get back a Some. Since the password is correct password_verify() 
will return TRUE and filter() will return $this. So findByLogin() will return a Some. However, if the email is correct 
and the password is wrong then password_verify() will return false and filter will return a None which will be returned 
by findByLogin(). If the email is not found then findByEmail() will return a None. Since None::filter() ignores the 
callback and always returns $this the None you get back from findByEmail() will immediately be returned by findByLogin().

Mapping
-------

What if you have a number and you want to do math on it and get back the result?

    $intOption->map(function(int $number) {
        return $number * 2;
    });

What if you want to get the value as a float Some?

    $intOption->flatMap(function(int $number) { 
        return Float\Option::new($number * 2.5);
    });


Replacing An Option
-------------------

Suppose you are looking for all orders made by a customer and you want to print the number of orders. The getOrders()
method might return an array Option with an array of order objects.

    $orders = $customer->getOrders()
                ->map(function(array $orders) { return count($orders); })
                ->orElse(Int\Option::new(0));

If getOrders() returns a Some it will be replaced by an integer Some containing the number of orders. Since orElse() is
called on a Some the else will be ignored and $orders will contain the mapped integer Some. But if getOrders() returns a
None the map is ignored and the None is returned. Now that orElse() is called on a None it will be replaced by the else
and $orders will contain the integer Some containing 0.

What if a repository searches an internal identity map for an object and if not found it searches a cache? If
not found in the cache it would then query the database. If it still doesn't find anything you want to create the
entity.

    public function find(int $id): Option
    {
        return $this->identityMap->find($id)
                ->orCall(function() use($id) { return $this->cache->find($id); })
                ->orCall(function() use($id) { return $this->db->find($id); });
    }
    
    ...
    
    $entity = $userRepository->find(5)->orElse(Option::new(new Entity()));

In this example the find() method first queries the identity map which returns an Option. If that Option is a None it
will search the cache and if that returns a None it will search the database. What you get back is the first Some that
is found or a None if it gets all the way to the end without finding anything. If the find() method returns a Some
then that Some will be the value of $entity. If find() returns a None then the $entity will be a Some containing the newly
created Entity.

Notice the use of orCall(). We could have used orElse($this->cache->find($id)) but that would mean querying the cache
even if the entity was found in the identity map. That's bad so by using orCall() it will only call the function if the
identity map returns a None.

By chaining Options together like this you can make several attempts at getting a usable value. You could get an Option,
apply a map() then a filter() and then an orElse() and finally a getOrElse().

Getting A Value With A Default
------------------------------

You want to print a users phone number but you might not have their phone number:

    echo $user->getPhone()->getOrElse('unknown');

This will print the phone number if it is a Some or it will print "unknown" if it is a None.

Sometimes the else will be the result of an expensive operation which you do not want to perform if you don't have to.
For example, you can get a session and generate a new session if one doesn't exist.

    $session = getSession()->getOrCall('createSession');

This example assumes there is a function called createSession(). Only if there is no session will a new session be
created. $session will now contain a session, not an Option.

Optional Object Properties
--------------------------

What if you have a User class that expects a name, email address and optionally a phone number?

    class User
    {
        ...
         
        public function __construct(string $name, string $email, String\Option $phoneNumber)
        {
            ...
        }
    }
     
    //we do have a phone number
    $user = new User('John Dough', 'john@example.com', String\Option::new('123-456-7890'));
     
    //we do not have a phone number
    $user = new User('John Dough', 'john@example.com', String\Option::new(null);

Now that you have your user object how do you know if that user has a phone number? Without options you would need to
either add a hasPhoneNumber() method to your User object or you would have to get the phone number and then check if it
is null or empty. With options you know that getPhoneNumber() will return a Some or a None and you can ask if it
has a value. Your user object doesn't care.

    //without options with PHP < 5.5
    $phone = $user->getPhone();
    if(empty($phone)) {
        $phone = 'unknown';
    }
     
    //without options with PHP >= 5.5
    if(empty($user->getPhone())) {
        $phone = 'unknown';
    }
     
    //with options
    if($user->getPhone()->isEmpty()) {
        $phone = 'unknown';
    }
    //or better
    $phone = $user->getPhone()->getOrElse('unknown')
    //or
    if($user->getPhone()->isDefined()) { ... }
    
PHP 5.5 is better than PHP 5.4 and earlier but without options what will getPhone() return if the phone is unknown?
Will it return null, an empty string or possibly false? You might have different developers writing very different code
to determine whether or not there is a phone number but Options provide a standard way of determining this.