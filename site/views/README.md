Lydia, the site/view directory
==============================

Here you can place you own views. Create a subdirectory, named as the module they belongs to.
Use `CLydia::LoadView()` or `CCObject:.LoadView()` to load the view.

You can overide the default Lydia views that exists in `LYDIA_INSTALL_PATH/views` by copy
each file to this directory. The `LoadView()`-method starts by looking in this directory
before it gets the file from `LYDIA_INSTALL_PATH/views`.

