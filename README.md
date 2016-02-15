PHP Options
===========


Options are generally used in programming as a return value for a function that may or may not have a value to return.
For example, when you call a find() method on a repository object it may or may not find the entity you are looking for.

My favorite example of how an Option could be used in PHP is the strpos() function. Instead of returning an integer or
FALSE it could return a Some(integer) or a None. This would allow you to do the following:

    if(strpos('abc', 'a')->isDefined()) { ... }
    
    instead of
    
    if(false === strpos('abc', 'a')) { ... }

You can also use Options when assigning a value to an optional property. For example, a User object might take an ID,
name and email address. When pulling the user from a database you have an ID but when you create a new User object you
do not have an ID until after it has been inserted into the DB. The User might also have an optional phone number. You
can easily determine whether or not a User object has been persisted by calling $user->getId()->isDefined() without the 
User object needing to understand its origins or whether or not it is persisted.

This implementation of Options also allows you to specify what type of data it contains. It comes with a base Option that
can accept any type of data as well as Option classes for each PHP data type and for DateTime objects. It can be extended
to create custom Options for any other type of data you require whether it be a specific class or an array that meets
specific criteria.

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

These classes are extended by 10 additional Option types, one for each PHP data type and for DateTime objects:

* Arr for arrays (marrky\Option\Type\Arr\Option)
* Bool for booleans (marrky\Option\Type\Bool\Option)
* Callback for callbacks (marrky\Option\Type\Callback\Option)
* DateTime for DateTime objects (marrky\Option\Type\DateTime\Option)
* Float for floats (marrky\Option\Type\Float\Option)
* Int for integers (marrky\Option\Type\Int\Option)
* Null for null values (marrky\Option\Type\Null\Option)
* Object for any type of objects (marrky\Option\Type\Object\Option)
* Resource for resources (marrky\Option\Type\Resource\Option)
* String for strings (marrky\Option\Type\String\Option)

Each of these extend the base Option class and are extended by their own Some and None classes. So Option\Type\Arr\Option
extends Option\Option while Option\Type\Arr\Option\Some and Option\Type\Arr\Option\None both extend Option\Type\Arr\Option.
The only exception is that Option\Type\DateTime\Option extends Option\Type\Object\Option.

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

In this example you specify that your function returns an Option. Since PHP will require a return value of Some or None
you know exactly what you will be getting.

Creating Options
----------------

You create an Option by instantiating a Some class or a None class. When creating a Some you must provide a value which 
will be stored inside the Some. Also, when creating a Some you must provide a value of the correct type. The base Some
can receive values of any type including NULL. A String Some can only accept a string, even an empty string. Creating a
Some with the wrong type of value will throw a TypeError.

When creating a Some with the wrong type no type conversion is made on the value. This means that passing 1 to a String
Some will not convert it to "1". You will get a TypeError. Likewise passing "1" to an Int Some will not cast it to an
integer and passing 1 to a Float Some will not cast it to a float.

    new Some(1);// works
    new Some('1');// works
    new Some(null);// works
    
    new Int\Some(1);// works
    new Int\Some('1');// throws TypeError
    new Int\Some(1.0);// throws TypeError
    new Int\Some(null);// throws TypeError
    new Int\Some('');// throws TypeError
    
    new Null\Some(null);// works
    new Null\Some();// works
    new Null\Some('');// throws TypeError
    
    new None(); // works
    new None(1); // the 1 is ignored
    new None(''); // the empty string is ignored

Methods For Determining Option Type
-----------------------------------

When you receive an Option you may need to know whether you got a Some or a None.

###isDefined() isSome()

These methods are interchangeable.

Some will always return TRUE.  
None will always return FALSE.

###isEmpty() isNone()

These methods are interchangeable. 

Some will always return FALSE.  
None will always return TRUE.

Methods For Retrieving Option Values
------------------------------------

###get()

Some will always return the stored value.  
None will always return itself.

    $some = new Some(5);
    $some->get(); //returns 5
    
    $none = new None;
    $none->get(); //returns None

###getOrElse($else)

Some will always return the stored value.  
None will always return $else.

    $some = new Some(5);
    $some->getOrElse(7); //returns 5
    
    $none = new None;
    $none->getOrElse(7); //returns 7

Replacing An Option
-------------------

###orElse(Option $else)

Some will always return itself.  
None will always return $else.

    $some = new Some(5);
    $some->orElse(new Some(7)); //returns Some(5)
    
    $none = new None;
    $none->orElse(new Some(7)); //returns Some(7)

###orElseCall(callable $else)

The $else function must return an Option when passed to a None or an InvalidArgumentException will be thrown.

Some will always return itself.  
None will always call $else and return its return value.

    $some = new Some(5);
    $some->orElseCall(function() { return new Some(7); }); //returns Some(5)
    
    $none = new None;
    $none->orElseCall(function() { return new Some(7); }); //returns Some(7)

Filtering An Option
-------------------

###filter(callable $filter)

$filter must be a callable that accepts the options value and returns a boolean. So with Option\Type\Bool\Option the
callable must expect a boolean value.

Some will return itself if the callback returns TRUE, otherwise it returns a None. It will return the same type of none 
as itself, so a String Some will return a String None.  
None always returns itself.

    $some = new Some(5);
    $some->filter(function(int $value) { return $value > 1; }); //returns Some(5)
    $some->filter(function(int $value) { return $value > 5; }); //returns None
    
    $none = new None;
    $none->filter(function(int $value) { return $value > 1; }); //returns None
    $none->filter(function(int $value) { return $value > 5; }); //returns None

Mapping an Option
-----------------

###map(callback $map)

$map takes the Option value and returns any value. The value is wrapped in an Option.

Some will return a Some of the same type if the callback returns the same data type, otherwise it returns a base Some.
If you pass a callback function to a String Some and the callback returns a String it will return a String Some but it 
will return a base Some if the callback returns an integer. If the callback returns an Option you will get an Option 
containing another Option.  
None will always return itself.

    $some = new Some(5);
    $some->map(function(int $value) { return $value * 2; }); //returns Some(10)
    $some->map(function(int $value) { return (string) $value; }); //returns Some('5')
    $some->map(function(int $value) { return new Some($value); }); //returns Some(Some(5))
    $some->map(function(int $value) { return new None; }); //returns Some(None)
    
    $intSome = new Int\Some(5);
    $some->map(function(int $value) { return $value * 2; }); //returns Int\Some(10)
    $some->map(function(int $value) { return (string) $value; }); //returns Option\Some('5')
    
    $none = new None;
    $none->map(function(int $value) { return $value * 2; }); //returns None
    $none->map(function(int $value) { return (string) $value; }); //returns None
    $none->map(function(int $value) { return new Some($value); }); //returns None
    $none->map(function(int $value) { return new None; }); //returns None

###flatMap(callback $map)

This works in much the same way as map() except it will never return an Option containing another Option.

    $some = new Some(5);
    $some->flatMap(function(int $value) { return $value * 2; }); //returns Some(10)
    $some->flatMap(function(int $value) { return (string) $value; }); //returns Some('5')
    $some->flatMap(function(int $value) { return new Some($value); }); //returns Some(5)
    $some->flatMap(function(int $value) { return new None; }); //returns None
    
    $intSome = new Int\Some(5);
    $some->flatMap(function(int $value) { return $value * 2; }); //returns Int\Some(10)
    $some->flatMap(function(int $value) { return (string) $value; }); //returns Option\Some('5')
    
    $none = new None;
    $none->flatMap(function(int $value) { return $value * 2; }); //returns None
    $none->flatMap(function(int $value) { return (string) $value; }); //returns None
    $none->flatMap(function(int $value) { return new Some($value); }); //returns None
    $none->flatMap(function(int $value) { return new None; }); //returns None

Performing An Operation Using An Options Value
----------------------------------------------

###foreach(callback $each)

This method simply calls the $each function passing in the Options value. It never returns anything.

Some calls the $each function passing in the Options value. It returns nothing.  
None ignores the callback and does nothing. It returns nothing.

    $some = new Some('Hello');
    $some->foreach(function(string $value) { echo $value; }); //echoes 'Hello', returns nothing.
    
    $some = new Some('/path/to/file');
    $some->foreach(function(string $value) { unlink($value); }); //deletes a file, returns nothing.
    
    $none = new None;
    $none->foreach(function(string $value) { echo $value; }); //does nothing, returns nothing.

Testing Equality Between Two Options
------------------------------------

###equals(option $option)

The equals method checks if the two Options are the same type and if their values are equal. and returns TRUE if they
are equal, FALSE if they are not.

Some returns TRUE if the provided Option is the same type and contains the same value, otherwise FALSE;  
None returns TRUE if the provided Option is the same type of None, otherwise FALSE.

    $some1 = new Some(1);
    $some1->equals(new Some(1)); //returns TRUE
    $some1->equals(new Some('1')); //returns FALSE
    $some1->equals(new Some(2)); //returns FALSE
    $some1->equals(new None); //returns FALSE
    
    $intSome = new Int\Some(1);
    $intSome->equals($some1); // returns false
    
    $none = new None;
    $none->equals(new None); // returns TRUE
    $none->equals(new Int\None); // returns FALSE
    $none->equals($some1); // returns FALSE

Creating Custom Options
=======================

Creating custom options is simple. All you have to do is create an Option class that extends the base Option class or
another appropriate Option class. Give it a validate($value) method that checks that the value is of the right type and
returns a boolean. Then create a new Some class and None class in the same namespace as your Option class, have them
extend your new Option class and mix in the BaseSome or BaseNone trait.

Entity Option Example
---------------------

Suppose you have a User object and a User repository where you get your User Objects. Here's a User Option:

User/Option.php
    
    namespace User\Option;
    
    use User;
    
    class Option extends \marrky\Option\Type\Object\Option
    {
        protected function validate($value): bool
        {
            return parent::validate($value) && $value istanceof User;
        }
    }

User/Some.php

    namespace User\Option;
    
    use maarky\Option\Component\BaseSome;
    
    class Some extends Option
    {
        use BaseSome;
    }

User/None.php

    namespace User\Option;
    
    use maarky\Option\Component\BaseNone;
    
    class None extends Option
    {
        use BaseNone;
    }

In this case you don't really need the validate() method to call tha parent validate() method since you know that
anything that is an instance of User will also be an object but I think it's a good idea to make sure that your Option
value is valid with its parent.

You would use this Option in your repository class in the same way as we did in the example above in the Basics section.
The only difference is you would use this Option rather than the base Option:

    use User\Option\Option;
    use User\Option\None;
    use User\Option\Some;
    
    ...
    
    public function find(int $id): Option
    {
        $result = $this->db->execute("some SQL");
        if(!$result->rowCount()) {
            return new None;
        }
        $entity = new User($result->fetch());
        return new Some($entity);
    }

Array Option Example
--------------------

You might also be working with an array and you want to be sure you get the type of array you want. For example:

Point/Option.php
    
    namespace Point\Option;
    
    class Option extends \marrky\Option\Type\Arr\Option
    {
        protected function validate($value): bool
        {
            return parent::validate($value) && 2 == count($value) && isset($value['lat']) && isset($value['lon'])
                   && is_float($value['lat']) && is_float($value['lon']);
        }
    }

Point/Some.php

    namespace Point\Option;
    
    use maarky\Option\Component\BaseSome;
        
    class Some extends Option
    {
        use BaseSome;
    }

Point/None.php

    namespace Point\Option;
    
    use maarky\Option\Component\BaseNone;
    
    class None extends Option
    {
        use BaseNone;
    }

And this is how you would use it:

    $point = new Point\Option\Some(['lat' => 123.456, 'lon' => 12.34]); //works
    $point->filter(function(array $point) { return $point['lat'] > 123}); // returns the Point\Option\Some
    $point->filter(function(array $point) { return $point['lat'] < 123}); // returns Point\Option\None
    new Point\Option\Some(['lat' => 123.456, 'lon' => 12.34, 'elevation' => 12345]); //throws TypeError
    
    $none = new Point\Option\None;
    $none->getOrElse(['lat' => 123.456, 'lon' => 12.34]); //returns the array

Overriding Equals
-----------------

You may ned to override the equals() method in the Some class. The None class is probably fine using the default since
it returns TRUE only when comparing it to another None of the same type. However, the Some method might need some
customization.

The base Some trait returns TRUE when comparing it to another Some of the same type with the same value (using ===). But
if you are comparing two length objects, one representing one foot and the other 12 inches, the default equals() method
will return false since the two length objects are different. However, you might want these to evaluate as equal since
they both represent the same length. Also, you might not care if the Option types are the same so long as their values
are equal.

    public function equals(Option $option): bool
    {
        if(!$option->isDefined()) {
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

Suppose you have a User class and an AdminUser class that extends User. You get the current user but you don't know if
you are getting a User or an AdminUser and you only want an AdminUser.

    //$user is an AdminUser
    $user = getCurrentUser()->filter(function(User $user) { return $user->isAdmin(); }); //$user is a Some
    
    //$user is an NOT an AdminUser
    $user = getCurrentUser()->filter(function(User $user) { return $user->isAdmin(); }); //$user is a None

Mapping
-------

What if you have a number and you want to do math on it and get back the result?

    $intOption->map(function(int $number) {
        return $number * 2;
    });

This will give you an integer Some containing the Some value times 2. What if you want to get the value as a float Some?

    $intOption->flatMap(function(int $number) { 
        return Float\Some($number * 2.0);
    });

If you did this with the map() method you would get a Some(Int\Some(5)) while flatMap() will give you a
Float\Some(5.0);

Replacing An Option
-------------------

Suppose you are looking for a user with a particular email address and you want to create a new user if one doesn't exist.

    $user = $userRepository->findByEmail('user@example.com')
                ->orElse(new Some(new User('user@example.com')));

If a user was found with the given email address then $user will be a Some Containing that user. But if no user is found
the find method will return a None which will be replaced by a Some containing the newly crated User object.

What if your repository searches an internal identity map for the object and if not found it searches a cache server? If
not found in the cache server it would then query the database. If it still doesn't find anything you want to create the
User.

    public function find(int $id): Option
    {
        return $this->identityMap->find($id)
                ->orElseCall(function() use($id) { return $this->cache->find($id); })
                ->orElseCall(function() use($id) { return $this->db->find($id); });
    }
    
    ...
    
    $user = $userRepository->find(5)->orElse(new Some(new User()));

In this example the find() method first queries the identity map which returns an Option. If that Option is a None it
will search the cache and if that returns a None it will search the database. What you get back is the first Some that
is found or a None if it gets all the way to the db query without finding anything. If the find() method returns a Some
then that Some will be the value of $user. If find() returns a None then the $user will be a Some containing the newly
created User.

Notice the use of orElseCall(). We could have used orElse($this->cache->find($id)) but that would mean querying the cache
even if the user was found in the identity map. That's bad so by using orElseCall() it will only call the function if the
identity map returns a None.

By chaining Options together like this you can make several attempts at getting a usable value. You could get an Option,
apply a map() then a filter() and then an orElse() and finally a getOrElse().

Getting A Value With A Default
------------------------------

You want to print a users phone number but you might not have their phone number:

    echo $user->getPhone()->getOrElse('unknown');

This will print the phone number if it is known or it will print "unknown" if their phone number is not known.

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
    $user = new User('John Dough', 'john@example.com', new String\Some('123-456-7890'));
    
    //we do not have a phone number
    $user = new User('John Dough', 'john@example.com', new String\None);

Now that you have your user object how do you know if that user has a phone number? Without options you would need to
either add a hasPhoneNumber() method to your User object or you would have to get the phone number and then check if it
is null or empty. With options you know that getPhoneNumber() will return a Some or a None and you can ask either if it
has a value. Your user object doesn't care.

    //without options with PHP < 5.5
    $phone = $user->getPhone();
    if(empty($phone)) { ... }
    //or
    if(!empty($phone)) { ... }
    
    //without options with PHP >= 5.5
    if(empty($user->getPhone())) { ... }
    //or
    if(!empty($user->getPhone())) { ... }
    
    //with options
    if($user->getPhone()->isEmpty()) { ... }
    //or
    if($user->getPhone()->isDefined()) { ... }
    
PHP 5.5 is better than PHP < 5.5 but without options what will getPhone() return if the phone is unknown? Will it return
null, an empty string or possibly false? One person might test it using empty() and another person might use is_null()
and somebody else might use == ''. With options you get consistency.