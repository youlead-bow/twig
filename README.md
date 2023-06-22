# twig
Twig extensions

Provides a {% includeDir %} tag for Twig
Provides a {% useDir %} tag for Twig
Provides a {% switch %} tag for Twig switch case statements.

## Usage

```twig
{% switch myVar %}
    {% case 'value1' %}
        {# ...code here to run for value1 #}
    {% case 'value2' %}
        {# ...code here to run for value2 #}
    {% default %}
        {# ...code here to run for default when no case matched #}
{% endswitch %}

{% includeDir '/popups' %}
{% includeDir '/popups' recursive %} {# for recursive usage #}

{% useDir '/form/specials' %}
{% useDir '/form/specials' recursive %} {# for recursive usage #}
```
