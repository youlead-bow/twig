# Twig extensions

Provides a {% includeDir %} tag for Twig  
Provides a {% useDir %} tag for Twig  
Provides a {% switch %} tag for Twig switch case statements.  

## 1. switch

### Usage

```twig
{% switch myVar %}
    {% case 'value1' %}
        {# ...code here to run for value1 #}
    {% case 'value2' %}
        {# ...code here to run for value2 #}
    {% default %}
        {# ...code here to run for default when no case matched #}
{% endswitch %}
```

## 2. includeDir

### Usage

```twig
{% includeDir '/popups' %}
```
The files in the directory will be included alphabetically.  

### Recursive usage

To include all files within a given directory recursive simply add the keyword *recursive* to your include statement:

```twig
<div class="modal-container">
    {% includeDir '/popups' recursive %}
</div>
```

Now also the popups from the directories */popups/user* and */popups/system* etc. will be included.

__Caution:__ The templates will be included alphabetically as well, including the directories. Thus the template */popups/footer.twig* will be included before the templates from the directory */popups/system* followed by */popups/user* followed by a possible */popups/zebraHeader.twig*. It is recommended to use includeDir only for templates which do __not__ require a specific order.

### Variables

As known from the Twig Core *include* you can control the available variables with the keywords *with* and *only* (compare: [include](https://twig.symfony.com/doc/2.x/tags/include.html))

```twig
<div class="modal-container">
    {# only the foo variable will be accessible #}
    {% includeDir '/modals' recursive with {'foo': 'bar'} only %}
</div>
```

## 2. useDir

### Usage

```twig
{% useDir '/form/specials' %}
```
The files in the directory will be included alphabetically.

### Recursive usage

To use all files within a given directory recursive simply add the keyword *recursive* to your use statement:

```twig
{% useDir '/form/specials' recursive %}
```
Now also the popups from the directories */form/specials* and */form/field* etc. will be used.

__Caution:__ The templates will be used alphabetically as well, including the directories. Thus the template */form/field/select.twig* will be included before the templates from the directory *form/specials*. It is recommended to use useDir only for templates which do __not__ require a specific order.
