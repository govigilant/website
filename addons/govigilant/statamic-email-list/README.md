# Statamic Email List

> Statamic Email List is a Statamic addon that provides backend e-mail list functionality.

## Features

- Provides a way to store email lists
- Supports multiple lists and additional data
- Confirmation E-mails
- Automatically deletes unconfirmed addresses

## How to Install

> This addon requires Eloquent

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

``` bash
composer require govigilant/statamic-email-list
```

## How to Use

This package does not provide a front-end implementation, just the backend routes for submitting them and sending confirmation mails.
You can use the following snippet to submit E-mail addresses:

```antlers
<form method="POST" action="{{ route:statamic.email-list.submit }}">
    <div>
        <input type="hidden" name="list" value="newsletter"/>
        {{ csrf_field }}
        <label for="email-address">Email address</label>
        <input id="email-address" name="email" type="email" autocomplete="email" required placeholder="Enter your email">
        <button type="submit">
            Submit E-mail
        </button>
    </div>
    {{ get_errors:all }}
        <div class="text-red-500">
            <ul>
                {{ messages }}
                <li>{{ message }}</li>
                {{ /messages }}
            </ul>
        </div>
    {{ /get_errors:all }}
</form>
```

The `email` and `list` fields are required. You may optionally add data as an array to the `data` field.

Once submitted the package will sent a confirmation mail via the scheduler.
E-mail addresses that are not confirmed within three days are automatically deleted.
