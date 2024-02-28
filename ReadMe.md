
A minimal wordpress plugin to help you hide your login url.

## How it work

To correctly access `wp-login.php`, you need to provide a correct pair of parameters. The default parameters are `wp-login.php?lock=some_string`. You can either modify the key and value before uploading the plugin, or edit them in the `wp-admin` page.

### Function notes

`forbid_login_with_lock` will check every unlogin visiting to `wp-login.php` and try to add session for correct parameters or redirect visitor to home page.

`my_authenticate` is just an additional protect to avoid login without correct session set.

## Usage

> Note: **Please make sure you have some way to disable this plugin without new login before activate it, you may accidently lock your self out from admin page if something went wrong.**

Just replace following two values in `locklogin.php` to whatever you like:

```php
const LC_TOKEON_NAME = "lock";
const LC_TOKEON_VALUE = "some_string";
// wp-login.php?lock=some_string
```

After plugin activate, you can only visit `wp-login.php` with the replaced parameters.