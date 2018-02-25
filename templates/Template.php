<?php

class Template
{
    public function generate($content_view, $params = null)
    {
        $loader = new Twig_Loader_Filesystem('templates');
        $twig = new Twig_Environment($loader, array(
            'cache' => 'cache', 'auto_reload' => true, 'debug' => true
        ));
        $twig->addGlobal('session', $_SESSION);
        $twig->addExtension(new Twig_Extension_Debug());

        $params['user'] = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        $header = $twig->loadTemplate('header.tmpl');
        $template = $twig->loadTemplate($content_view);
        $footer = $twig->loadTemplate('footer.php');

        echo $header->render($params);
        echo $template->render($params);
        echo $footer->render($params);
    }
}
