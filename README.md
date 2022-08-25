
![Adeliom](https://adeliom.com/public/uploads/2017/09/Adeliom_logo.png)
[![Quality gate](https://sonarcloud.io/api/project_badges/quality_gate?project=agence-adeliom_easy-seo-bundle)](https://sonarcloud.io/dashboard?id=agence-adeliom_easy-seo-bundle)

# Easy SEO Bundle


## Installation

Install with composer

```bash
composer require agence-adeliom/easy-seo-bundle
```

## Documentation

### Customisation

```yaml
# config/packages/easy_seo.yaml
easy_seo:
  title:
    suffix: ACME # Change the title suffix
    separator: '|' # Change the title separator
  breadcrumbs:
    class: 'breadcrumb'
    item_class: 'breadcrumb-item'
    link_class: ''
    current_class: 'active'
    separator: '>'
    separator_class: 'breadcrumb-separator'
```

### Add SEO to your entity
#### Entity
```php
use Adeliom\EasySeoBundle\Traits\EntitySeoTrait;

class Article {

    use EntitySeoTrait;

}
```
#### CRUD Controller
```php
class ArticleCrudController extends AbstractCrudController
{
    public function configureFields(string $pageName): iterable
    {
        yield SEOField::new("seo");
    }
}
```
#### Twig template
```php
# Render the title
{{- seo_title(object.seo) -}}

# Render the metadatas
{{- seo_metas(object.seo) -}}

# Render the breadcrumb
{{- seo_breadcrumb() -}}
```

### Events

#### easyseo.title
```php
use Symfony\Contracts\EventDispatcher\Event;

$dispatcher->addListener('easyseo.title', function (Event $event) {
    // will be executed when the easyseo.title event is dispatched
    
    // Get the title
    $title = $event->getArgument("title");
    
    // Set the title
    $event->setArgument("title", "custom title");
});
```
#### easyseo.render_meta
```php
use Symfony\Contracts\EventDispatcher\Event;

$dispatcher->addListener('easyseo.render_meta', function (Event $event) {
    // will be executed when the easyseo.render_meta event is dispatched
    
    // Get SEO data
    $seoData = $event->getArgument("datas");
    
    // Set SEO data
    $event->setArgument("datas", $seoData);
});
```
#### easyseo.breadcrumb
```php
use Symfony\Contracts\EventDispatcher\Event;

$dispatcher->addListener('easyseo.breadcrumb', function (Event $event) {
    // will be executed when the easyseo.breadcrumb event is dispatched
    
    // Get breadcrumb's items
    $items = $event->getArgument("items");
    
    // Set breadcrumb's items
    $event->setArgument("items", $items);
});
```

## Troubleshooting

- ```Adeliom\EasySeoBundle\Entity\SEO is not a valid entity or mapped super class.```

In case of using php 8, old php 7 annotations are not supported by default, add those lines in your doctrine package configuration file :
```yml
doctrine:
    orm:
        mappings:
            EasySeoBundle:
              type: annotation
              alias: Adeliom\EasySeoBundle
              prefix: Adeliom\EasySeoBundle\Entity
              dir: "src/Entity"
```

## License

[MIT](https://choosealicense.com/licenses/mit/)


## Authors

- [@arnaud-ritti](https://github.com/arnaud-ritti)


