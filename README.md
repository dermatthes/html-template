
[![Downloads this Month](https://img.shields.io/packagist/dm/html5/html-template.svg)](https://packagist.org/packages/html5/html-template)
[<img src="https://travis-ci.org/dermatthes/html-template.svg">](https://travis-ci.org/dermatthes/html-template)
[![Latest Stable Version](https://poser.pugx.org/html5/html-template/v/stable)](https://github.com/dermatthes/html-template/releases)


# Html5 Template - Template Rendering
angularjs-like inline-templates for PHP7.

> Planned as replacement for PHPTAL - which is quite fine but not enough extensible
> for cluster / platform usage.


Gismo Template does not precompile any code. It generates the pages on the fly.


Features:
* Works with HTML5 and XHTML (XHTML preferred)
* IDE supported template-development
* Generate HTML, plain-Text or struct-data from one file
* Supports macros
* Import other templates from virtual storage (cloud-ready)
* Verbose and useful error-messages with lineNo and xpath
* Secure and powerful expression engine (Symfony Component also used by Twig)

## Example


Template:
```html
<div go-if="showHeader==true">Welcome {{ user.name }}</div>
<div go-foreach="products as curProduct">
    <span go-bind="curProduct.name"></span>
</div>
```

Parser:
```php
$template = new HtmlTemplate();
echo $template->render("template.xhtml", ["showHeader" => true, "producs"=> [ new Product("prod1"), new Product("Product2)" ]]);
```


## Install using Composer

```
composer require html5/template
```


## Attributes

 * `go-if`: Condition
 * `go-foreach`: Loop
 * `go-repeat`: Loop
 * `go-continue`: Loop start-over
 * `go-break`: Quit loop
 * `go-bind`: Inject string data
 * `go-html`: Inject html data


### Conditions

```html
<div go-if="name == 'Matthias'">
    Hello {{ name }}
<div>
```

### Foreach Loops

```html
<div go-foreach="products as product">
    Product Title: {{product.title}} 
</div>
```

```html
<div go-foreach="data as curKey => curVal">
    Key: {{curKey}} Value: {{curVal}}
</div>
```

### Repeat Loops

```html
<div go-repeat="100 indexBy myIndex">
    Line {{ myIndex }}: Hello
</div>
```

### break / continue a loop

You can use conditions to break or continue a loop

```
<div go-loop="100 indexBy index">
    <go-break go-if="! count(productList) < index"/>
    ...
</div>
```

or use continue to skip elements:

```
<div go-foreach="products as product">
    <go-continue go-if="product.isHidden == true"/>
    ...
</div>
```



### Bind escaped Value

```html
<span go-bind="user.name">Name to show if user.name is null</span>
```

The data will be escaped by `htmlspecialchars()`

### Bind Html-Code

```html
<div go-html="page.htmlCode">Show <b>this</b> if page.htmlCode is null</div>
```

### Add CSS Classes dynamicly

```html
<div go-class="{cssClassName: name=='Matthes', otherClassName: highlight==true}">..</div>
```

### Show/Hide Elements

```html
<div go-show="showIt==true"></div>
```

```html
<div go-hide="hideIt==true"></div>
```

> if hidden it adds the css-class `ng-hide` (also used by angularjs)

## Elements

 * `go-text`: Inject Text
 * `go-define`: Define Variables in Scope
 * `go-dump`: Dump a variable or scope
 * `go-macro`: Define a macro
 * `go-callmacro`: Call a macro
 * `go-struct`: Return array data sections defined by go-section
 * `go-section`: Define or overwrite struct data
 


### Interceptors





### Macros

Macros can be used to create Output on multiple positions. To define a macro use the `go-macro`-Element:

```html
<go-macro name="printDemoTable(headers, data)">
    <table>
        <thead>
            <tr>
                <td go-foreach="headers as curHeader">{{ curHeader }}</td>
            </tr>
        </thead>
        <tbody>
            ...
        </tbody>
    </table>
</go-macro>
```

To generate your Table use `go-callmacro` - Element:

```html
<body>
    <go-callmacro name="printDemoTable(shoppingCart.headers, shoppingCart.items)"/>
</body>

```


## Benchmark

Small Example from above:
```
Parsing + Rendering: <0.01s
```

Big demo with ~2000 Lines of template-code
```
Template-Parsing: <10ms
Rendering: <15ms
```


## Installing V8JS on Ubuntu 16.04

Since there is no apt package for v8js you have to do it manually:

```
sudo add-apt-repository ppa:pinepain/libv8-5.2
sudo apt-get update
sudo apt-get install libv8-dev  g++ cpp php-pear php7.0-dev
sudo pecl install v8js

sudo bash
echo "extension=v8js.so" > /etc/php/7.0/apache2/conf.d/20-v8js.ini
echo "extension=v8js.so">/etc/php/7.0/cli/conf.d/20-v8js.ini

service apache2 restart
```




## Author

Written 2016 by Matthias Leuffen http://leuffen.de