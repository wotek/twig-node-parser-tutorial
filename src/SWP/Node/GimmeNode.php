<?php

namespace SWP\Node;

/**
 * Gimme twig node.
 */
class GimmeNode extends \Twig_Node
{
    private static $count = 1;

    /**
     * GimmeNode constructor.
     *
     * @param \Twig_Node                 $annotation
     * @param \Twig_Node_Expression|null $parameters
     * @param \Twig_Node                 $body
     * @param int                        $lineno
     * @param null                       $tag
     */
    public function __construct(
        \Twig_Node $annotation,
        \Twig_Node_Expression $parameters = null,
        \Twig_Node $body,
        $lineno,
        $tag = null
    ) {
        $nodes = [
            'body' => $body,
            'annotation' => $annotation,
        ];

        if (!is_null($parameters)) {
            $nodes['parameters'] = $parameters;
        }

        parent::__construct($nodes, [], $lineno, $tag);
    }

    /**
     * {@inheritdoc}
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $i = self::$count++;

        $compiler
            ->addDebugInfo($this)
            ->write('$loader'.$i." = \$this->env->getExtension('SWP\Extension\GimmeExtension')->getLoader();\n");

        $compiler
            ->write('')->subcompile($this->getNode('annotation'))->raw(' = $loader'.$i.'->load("')->raw($this->getNode('annotation')->getNode(0)->getAttribute('name'))->raw('", ');
        if ($this->hasNode('parameters')) {
            $compiler->subcompile($this->getNode('parameters'));
        } else {
            $compiler->raw('null');
        }
        $compiler->raw(");\n")
            ->write('if (')->subcompile($this->getNode('annotation'))->raw(" !== false) {\n")
            ->indent()
            ->subcompile($this->getNode('body'))
            ->outdent()
            ->write("}\n");

        $compiler
            ->write('unset(')->subcompile($this->getNode('annotation'))->raw(');');
    }
}

//  Compiling this template:
//
//  {% gimme article with {'foo': 'bar'} %} {{ dump(article) }} {% endgimme %}
//
//  will generate php code like this:
//
//  // line 1
//  $loader1 = $this->env->getExtension('SWP\Extension\GimmeExtension')->getLoader();
//  $context["article"] = $loader1->load("article", array("foo" => "bar"));
//  if ($context["article"] !== false) {
//      echo " ";
//      echo twig_var_dump($this->env, $context, ($context["article"] ?? null));
//      echo " ";
//  }
//  unset($context["article"]);    }
