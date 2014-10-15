Twitter Bootstrap Bundle for Symfony
=========================

Add the Twitter Bootstrap and jQuery frameworks inside the Symfony2 framework.

Installation
------------

This bundle requires JQuery Bundle, and it will be installed automatically.

### Add bundle to your composer.json file

    // ...
    "require": {
        // ...
        "symfony-bundle/bootstrap-bundle": "3.2.*";
        // for Bootstrap 3.2
        // ...
    },
    "scripts": {
        // ...
        "post-install-cmd": [
            // ...
            // insert the both lines before Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets
            "Anezi\\Bundle\\JQueryBundle\\Composer\\ScriptHandler::copyJQueryToBundle",
            "Anezi\\Bundle\\BootstrapBundle\\Composer\\ScriptHandler::copyFilesToBundle",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets",
            // ...
        ],
        "post-update-cmd": [
            // ...
            // insert the both lines before Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets
            "Anezi\\Bundle\\JQueryBundle\\Composer\\ScriptHandler::copyJQueryToBundle",
            "Anezi\\Bundle\\BootstrapBundle\\Composer\\ScriptHandler::copyFilesToBundle",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets",
            // ...
        ]
    },

### Add bundle to your application kernel

    // app/AppKernel.php
    
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Anezi\Bundle\JQueryBundle\JQueryBundle(),
            new Anezi\Bundle\BootstrapBundle\BootstrapBundle(),
            // ...
        );
    }

### Download the bundle using Composer

    $ composer update symfony-bundle/bootstrap-bundle
    
### Install assets

Update assets using composer post command:

    $ composer run-script post-update-cmd

Usage
-----

Refer to the jquery and bootstrap files in your HTML template, e.g.:

    <script type="text/javascript" src="{{ asset('bundles/jquery/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bundles/bootstrap/js/bootstrap.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('bundles/bootstrap/css/bootstrap.css') }}">

### Horizontal form:

Activate the service:

    services:
        # ...
        bootstrap.twig.bootstrap_extension:
            class: Anezi\Bundle\BootstrapBundle\Twig\BootstrapExtension
            tags:
                - { name: twig.extension }

Then you can use the pre defined extension in your template. Example:

    {% form_theme form _self %}
    
    {% block form_start -%}
        {% set method = method|upper %}
        {%- if method in ["GET", "POST"] -%}
            {% set form_method = method %}
        {%- else -%}
            {% set form_method = "POST" %}
        {%- endif -%}
        {%- set attr = attr|merge({'class': 'form-horizontal', 'role': 'form', 'novalidate': true}) -%}
        <form name="{{ form.vars.name }}" method="{{ form_method|lower }}" action="{{ action }}"{% for attrname, attrvalue in attr %} {{ attrname }}="{{ attrvalue }}"{% endfor %}{% if multipart %} enctype="multipart/form-data"{% endif %}>
        {%- if form_method != method -%}
            <input type="hidden" name="_method" value="{{ method }}" />
        {%- endif -%}
    {%- endblock form_start %}
    
    {% block form_row -%}
        {% if not row_attr is defined %}
            {% set row_attr = {'class': 'form-group'} %}
        {% endif %}
        <div {% for attrname, attrvalue in row_attr %} {{ attrname }}="{{ attrvalue }}"{% endfor %} class="form-group {% if errors|length > 0 -%} has-error{%- endif %}">
            {{- form_label(form, label, { 'label_attr': { 'class': 'col-sm-3 control-label' } }) -}}
            <div class="col-sm-9">
                {% if not control_attr is defined %}
                    {% set control_attr = {'class': 'form-control'} %}
                {% endif %}
                {{- form_widget(form, { 'attr': control_attr }) -}}
                {{- form_errors(form) -}}
            </div>
        </div>
    {%- endblock form_row %}
    
    {% block form_errors -%}
        {% if errors|length > 0 -%}
            {%- for error in errors -%}
                <span class="help-block">{{ error.message }}</span>
            {%- endfor -%}
        {%- endif %}
    {%- endblock form_errors %}
    
    {% block form_label -%}
        {% if label is not sameas(false) -%}
            {% if not compound -%}
                {% set label_attr = label_attr|merge({'for': id}) %}
            {%- endif %}
            {% if required -%}
                {% set label_attr = label_attr|merge({'class': (label_attr.class|default('') ~ ' required')|trim}) %}
            {%- endif %}
            {% if label is empty -%}
                {% set label = name|humanize %}
            {%- endif -%}
            <label{% for attrname, attrvalue in label_attr %} {{ attrname }}="{{ attrvalue }}"{% endfor %}>{{ label|trans({}, translation_domain) }}
            {% if required -%}
                <span class="required" title="Required">*</span>
            {%- endif %}
            </label>
        {%- endif %}
    {%- endblock form_label %}
    
    {% block button_row -%}
        {{- form_widget(form) -}}
    {%- endblock button_row %}
    
        {{ form_start(form) }}
        {{ form_row(form.title) }}
        {{ form_group(form.path) }}
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                {{ form_row(form.save, { 'attr': {'class': 'btn btn-primary'} }) }}
                {{ form_row(form.cancel, { 'attr': {'class': 'btn btn-link'} }) }}
            </div>
        </div>
    {{ form_end(form) }}


Pygments (Highlight code) (Optional)
--------
### Installation

    sudo apt-get install python-pygments

TO DO.

### Usage

TO DO.

License
-------

This bundle is available under the MIT license.
