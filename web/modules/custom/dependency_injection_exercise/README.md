# Setup

1. Install this module.
2. Add the block 'Rest output block' to a page.

# Exercises

1. We've got a controller and block, both outputting roughly the same thing. Please refactor these so the call to fetch data happens in a service. Then, inject that service in both the controller and block instead of using \Drupal::service()
2. Take over the MailManager service and ensure all mails are redirected to "nope@doesntexist.com"
3. On the path /dropsolid/example/photos the breadcrumb should be Home > Dropsolid > Example > Photos
4. Take over the LanguageManager service in a way that doesn't preclude others from also taking over the LanguageManager service

# Documentation

- [Services and dependency injection in Drupal 8+](https://www.drupal.org/docs/drupal-apis/services-and-dependency-injection/services-and-dependency-injection-in-drupal-8)
- [Altering existing services, providing dynamic services](https://www.drupal.org/docs/drupal-apis/services-and-dependency-injection/altering-existing-services-providing-dynamic-services)
