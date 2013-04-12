Lydia, the view directory
==============================

Views are files containing HTML and PHP-code to create HTML (or other type of information) for the response. The resulting page is, in general, built up on one or more views. The views are loaded by `CLydia::ThemeEngineRender()` and has access to the theme-functions. 

It is good code practice to only use theme-functions in views, together with the variables that are sent to the view from the controller. Nothing stops you from accessing `$ly` or `CLydia::Instance()` from the view. But it makes more sence to keep the theme, views and template files separated from the Lydia core. Therefore we use the theme-functions as a layer between theme-related issues, like views, and core Lydia.

A view-file is named `name-of-view.tpl.php` where the extension `.tpl.php` states that it is a template-file loaded by the theme in the theme rendering phase.



Directories where views are stored
----------------------------------

There are two directories where views are stored:

`views`:

This is the default directory for all views. The views are usually placed in a subdirectory - named as the module they belong to - for example `views/CCUser`. The module could have a corresponding directory in the `src`-directory, such as `src/CCUser`.


`site/views`:

Here you place views specific to a custom site. You can override existing views in the directory `views` by adding a new view with the same name, that is same name of module and same name of view, in the directory `site/views`. This is the same strategy used by the autoloader for classes where the autoloader first looks for the class in the `site/src` and if not found it looks in the directory `src`.



Loading the view
----------------------------------

There is a method you can use to load the view, the method looks after the view in `site/views` and then in `views`.

`CLydia::LoadView()` is the actual implementation of the view loader.

`CCObject:LoadView()` is a wrapper of `CLydia::LoadView()` which makes it possible to use as `$this->LoadView()` in subclasses, for examples in controllers.

These methods can be used anywhere to find the path to the view.



Working with views in controllers
----------------------------------

The controllers prepares information for the views and stores them in the `CViewContainer` which is input to the theme rendering phase.

**Preparing views in a controller, storing in `$this->views` which is an instance of `CViewContainer`:**

```php
$data = array('user' => $this->user);

$this->views->SetTitle(t('User Control Panel'))
            ->AddIncludeToRegion('main', $this->LoadView('index.tpl.php'), $data);
            ->AddIncludeToRegion('sidebar', $this->LoadView('sidebar.tpl.php'), $data);
```

The views are then loaded in the theme-regions, in this example `main` and `sidebar`, through the theme template file, usually `themes/base/index.tpl.php`, when using the theme *base*.

**Loads views for specific regions in `themes/base/index.tpl.php`:**

```html
<div id='outer-wrap-main'>
  <div id='inner-wrap-main'>
    <?php if(region_has_content('primary')): ?><div id='primary' role='main'><?=render_views('primary')?></div><?php endif; ?>
    <?php if(region_has_content('sidebar')): ?><div id='sidebar' role='complementary'><?=render_views('sidebar')?></div><?php endif; ?>
  </div>
</div>
```




