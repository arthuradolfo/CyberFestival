dbObject - model implementation on top of the MysqliDb
Please note, that this library is not pretending to be a full stack ORM but a simple OOP wrapper for mysqlidb

<hr>
###Initialization

1. Include mysqlidb and dbObject classes. 

2. If you want to use model autoloading instead of manually including them in the scripts use autoload () method.

```php
require_once ("libs/MysqliDb.php");
require_once ("libs/dbObject.php");

// db instance
$db = new Mysqlidb ('localhost', 'user', '', 'testdb');
// enable class autoloading
dbObject::autoload ("models");
```

3. Create simple user class (models/user.php):

```php
class user extends dbObject {
  protected $dbTable = "users";
  protected $primaryKey = "id";
  protected $dbFields = Array (
    'login' => Array ('text', 'required'),
    'password' => Array ('text'),
    'createdAt' => Array ('datetime'),
    'updatedAt' => Array ('datetime'),
  );
}
```
###Insert Row
1. OOP Way. Just create new object of a needed class, fill it in and call save () method. Save will return 
record id in case of success and false in case if insert will fail.
```php
$user = new user;
$user->login = 'demo';
$user->password = 'demo';
$id = $user->save ();
if ($id)
  echo "user created with id = " . $id;
```

2. Using arrays
```php
$data = Array ('login' => 'demo',
        'password' => 'demo');
$user = new user ($data);
$id = $user->save ();
if ($id == null) {
    print_r ($user->errors);
    echo $db->getLastError;
} else
    echo "user created with id = " . $id;
```

3. Multisave

```php
$user = new user;
$user->login = 'demo';
$user->pass = 'demo';

$p = new product;
$p->title = "Apples";
$p->price = 0.5;
$p->seller = $user;
$p->save ();
```

After save() is call both new objects (user and product) will be saved.

###Selects

Retrieving objects from the database is pretty much the same process of a get ()/getOne () execution without a need to specify table name.

All mysqlidb functions like where(), orWhere(), orderBy(), join etc are supported.
Please note that objects returned with join() will not save changes to a joined properties. For this you can use relationships.

Select row by primary key

```php
$user = user::byId (1);
echo $user->login;
```

Get all users
```php
$users = user::orderBy ('id')->get ();
foreach (users as $u) {
  echo $u->login;
}
```

Using where with limit
```php
$users = user::where ("login", "demo")->get (Array (10, 20));
foreach (users as $u) ...
```

###Update
To update model properties just set them and call save () method. As well values that needed to by changed could be passed as an array to the save () method.

```php
$user = user::byId (1);
$user->password = 'demo2';
$user->save ();
```
```php
$data = Array ('password', 'demo2');
$user = user::byId (1);
$user->save ($data);
```

###Delete
Use delete() method on any loaded object. 
```php
$user = user::byId (1);
$user->delete ();
```

###Relations
Currently dbObject supports only hasMany and hasOne relations. To use them declare $relations array in the model class.
After that you can get related object via variable names defined as keys.

HasOne example:
```php
    protected $relations = Array (
        'person' => Array ("hasOne", "person", 'id');
    );

    ...

    $user = user::byId (1);
    // sql: select * from $persontable where id = $personValue
    echo $user->person->firstName . " " . $user->person->lastName . " have the following products:\n";
```

In HasMany Array should be defined target object name (product in example) and a relation key (userid).

HasMany example:
```php
    protected $relations = Array (
        'products' => Array ("hasMany", "product", 'userid')
    );

    ...

    $user = user::byId (1);
    // sql: select * from $product_table where userid = $userPrimaryKey
    foreach ($user->products as $p) {
            echo $p->title;
    }
```
###Timestamps
Library provides a transparent way to set timestamps of an object creation and its modification:
To enable that define $timestamps array as follows:
```php
protected $timestamps = Array ('createdAt', 'updatedAt');
```
Field names cant be changed.

###Validation and Error checking
Before saving and updating the row dbObject do input validation. In case validation rules are set but their criteria is not met
then save() will return an error with its description. For example:
```php
$id = $user->save();
if (!$id) {
    // show all validation errors
    print_r ($user->errors);
    echo $db->getLastQuery();
    echo $db->getLastError();
}
echo "user were created with id" . $id;
```
Validation rules must be defined in $dbFields array.
```php
  protected $dbFields = Array (
    'login' => Array ('text', 'required'),
    'password' => Array ('text'),
    'createdAt' => Array ('datetime'),
    'updatedAt' => Array ('datetime'),
    'custom' => Array ('/^test/'),
  );
```
First parameter is a field type. Types could be the one of following: text, bool, int, datetime or a custom regexp.
Second parameter is 'required' and its defines that following entry field be always defined.

###Array as return values
dbObject can return its data as array instead of object. To do that ArrayBuilder() function should be used in the beginning of the call.
```php
    $user = user::ArrayBuilder()->byId (1);
    echo $user['login'];

    $users = user::ArrayBuilder()->orderBy ("id", "desc")->get ();
    foreach ($users as $u)
        echo $u['login'];
```

Following call will return data only of the called instance without any relations data. Use with() function to include relation data as well.

```php
    $user = user::ArrayBuilder()->with ("product")->byId (1);
    print_r ($user['products']);
```
###Object serialization

Object could be easily converted to a json string or an array.

```php
    $user = user::byId (1);
    // echo will display json representation of an object
    echo $user;
    // userJson will contain json representation of an object
    $userJson = $user->toJson ();
    // userArray will contain array representation of an object
    $userArray = $user->toArray ();
```

###Examples

Please look for a use examples in tests/dbObjectTests.php file and test models inside the tests/models/ directory
