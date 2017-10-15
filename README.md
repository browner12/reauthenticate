# Reauthenticate

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

For pages that contain more sensitive operations, sometimes you wish to have the user reauthenticate themselves. This simple package provides the tools you need to quickly implement this functionality on your website.

## Install

Via Composer

``` bash
$ composer require browner12/reauthenticate
```

## Setup

Add the service provider to the providers array in `config/app.php`.

``` php
'providers' => [
    browner12\reauthenticate\ReauthenticateServiceProvider::class,
];
```

If you are using Laravel's automatic package discovery, you can skip this step.

## Publishing

While we provide sensible defaults, if you would like to customize this package simply publish the config file with the following command. 

``` php
php artisan vendor:publish --provider="browner12\reauthenticate\ReauthenticateServiceProvider"
```

## Wiring

Let's start by adding our new middleware to `App\Http\Kernel.php`.

```php
protected $routeMiddleware = [
    'auth'           => \Illuminate\Auth\Middleware\Authenticate::class,
    'auth.basic'     => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'bindings'       => \Illuminate\Routing\Middleware\SubstituteBindings::class,
    'can'            => \Illuminate\Auth\Middleware\Authorize::class,
    'guest'          => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'throttle'       => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'reauthenticate' => \browner12\reauthenticate\Reauthenticate::class,
];
```

We will need 2 routes for our reauthentication. One to show the form to enter a password, and another to process the input.

```php
Route::get('reauthenticate', 'ReauthenticateController@reauthenticate')->name('reauthenticate');
Route::post('reauthenticate', 'ReauthenticateController@processReauthenticate')->name('reauthenticate.process');
```

Now let's make the associated controller:

```sh
php artisan make:controller ReauthenticateController
```

This package offers a trait to use in your controller. This pattern gives you the flexibility to customize the controllers as you need, while also controlling the pieces that are important for the normal package operation.

The trait offers 2 methods: 

- `checkReauthenticationPassword()` - Checks the entered password against the known hash, and returns the requested URL if successful. Returns `false` on failure.
- `resetReauthenticationTimer()` - Stores the current time in the session as the last successful authentication. 

Now we will use this trait in our controller.

```php
namespace App\Http\Controllers;

use browner12\reauthenticate\Concerns\Reauthenticates;
use Illuminate\Http\Request;

class ReauthenticateController extends Controller
{
    use Reauthenticates;
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reauthenticate()
    {
        //load view
        return view('main/auth/reauthenticate');
    }

    /**
     * @param \Illuminate\Http\Request             $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processReauthenticate(Request $request)
    {
        //good password
        if ($url = $this->checkAuthorizationPassword($request->get('password'), $request->user()->password)){
        
            return redirect()->to($url);
        }
        
        //send back
        return back();
    }
}
```

We do not require your view to be formatted in any way, or name your inputs anything specific. In the example above, the input is named 'password', and we are pulling the current password hash off of the logged in user.

If you would like to reset the timer in any of your other controllers, for example when the user initially logs in, you can also use the `resetAuthorizationTimer()` method on this trait.

## Usage

Using the reauthentication feature is incredibly easy. Simply add the middleware to either your routes:

```php
Route::get('users', 'UserController')->middleware('reauthenticate');
```

or your controllers:

```php
class UserController extends Controller
{
    /**
     * constructor
     */
    public function __construct()
    {
        //parent
        parent::__construct();
    
        //middleware
        $this->middleware('auth');
    
        //reauthenticate
        $this->middleware('reauthenticate')->only(['index']);
    }
}
```

## Limitations

Currently this feature only works on GET requests. The reason for this is because we cannot redirect to a POST route. I do have a solution in mind that uses a dummy page with a form that automatically submits, but I am holding off to see what the interest for it is first.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email browner12@gmail.com instead of using the issue tracker.

## Credits

- [Andrew Brown][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/browner12/reauthenticate.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/browner12/reauthenticate/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/browner12/reauthenticate.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/browner12/reauthenticate.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/browner12/reauthenticate.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/browner12/reauthenticate
[link-travis]: https://travis-ci.org/browner12/reauthenticate
[link-scrutinizer]: https://scrutinizer-ci.com/g/browner12/reauthenticate/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/browner12/reauthenticate
[link-downloads]: https://packagist.org/packages/browner12/reauthenticate
[link-author]: https://github.com/browner12
[link-contributors]: ../../contributors
