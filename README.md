# User Roles Adjustments

WordPress mu-plugin for user editing in combination with the [Members](https://wordpress.org/plugins/members/) plugin. Retains user levels and limits user editing capabilities.

## Installation

1. Define the dropin path for `wordpress-muplugin`
`composer config --json --merge extra.dropin-paths '{ "wordpress/content/mu-plugins/": [ "type:wordpress-muplugin" ] }'`
1. Install `koodimonni/composer-dropin-installer` and `wearerequired/user-roles-adjustments`
`composer require koodimonni/composer-dropin-installer wearerequired/user-roles-adjustments`

Example of a `composer.json` for a site:

```json
{
  "name": "wearerequired/something",
  "require": {
    "koodimonni/composer-dropin-installer": "^1.0",
    "wearerequired/user-roles-adjustments": "^0.2"
  },
  "extra": {
    "dropin-paths": {
      "wordpress/content/mu-plugins/": [
        "type:wordpress-muplugin"
      ]
    }
  }
}
