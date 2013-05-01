<?php

/* @admin/base.html */
class __TwigTemplate_36ee7b6df5772144e5bdc65dbb48fa9f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html ";
        // line 2
        echo $this->env->getExtension('Template Tag Extension')->languageAttributes();
        echo ">
<head>

</head>
<body ";
        // line 6
        echo $this->env->getExtension('Template Tag Extension')->bodyClass("admin");
        echo ">

</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "@admin/base.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  29 => 6,  22 => 2,  19 => 1,);
    }
}
