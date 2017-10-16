# User Roles Adjustments

WordPress mu-plugin for user editing in combination with the [Members](https://wordpress.org/plugins/members/) plugin. Retains user levels and limits user editing capabilities.

## Installation

- Define the git repository
- Require `koodimonni/composer-dropin-installer` and `wearerequired/user-roles-adjustments`
- Define the drop in path

Example of a `composer.json` for a site:

```json
{
  "name": "wearerequired/something",
  "repositories": [
    {
      "type": "git",
      "url": "git@github.com:wearerequired/user-roles-adjustments.git"
    }
  ],
  "require": {
    "koodimonni/composer-dropin-installer": "dev-master",
    "wearerequired/user-roles-adjustments": "dev-master"
  },
  "extra": {
    "dropin-paths": {
      "wordpress/content/mu-plugins/": [
        "type:wordpress-muplugin"
      ]
    }
  }
}
