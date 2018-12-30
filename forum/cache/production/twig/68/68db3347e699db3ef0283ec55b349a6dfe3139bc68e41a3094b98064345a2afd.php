<?php

/* index_body.html */
class __TwigTemplate_49aa7bd8e64ca6fb5645cb34ae1db62cf8441dcee08520ec8d3eb57f26870661 extends Twig_Template
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
        $location = "overall_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_header.html", "index_body.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
<p class=\"";
        // line 3
        echo (isset($context["S_CONTENT_FLOW_END"]) ? $context["S_CONTENT_FLOW_END"] : null);
        echo " responsive-center time";
        if ((isset($context["S_USER_LOGGED_IN"]) ? $context["S_USER_LOGGED_IN"] : null)) {
            echo " rightside";
        }
        echo "\">";
        if ((isset($context["S_USER_LOGGED_IN"]) ? $context["S_USER_LOGGED_IN"] : null)) {
            echo (isset($context["LAST_VISIT_DATE"]) ? $context["LAST_VISIT_DATE"] : null);
        } else {
            echo (isset($context["CURRENT_TIME"]) ? $context["CURRENT_TIME"] : null);
        }
        echo "</p>
";
        // line 4
        if ((isset($context["S_USER_LOGGED_IN"]) ? $context["S_USER_LOGGED_IN"] : null)) {
            echo "<p class=\"responsive-center time\">";
            echo (isset($context["CURRENT_TIME"]) ? $context["CURRENT_TIME"] : null);
            echo "</p>";
        }
        // line 5
        echo "
";
        // line 6
        // line 7
        if ((isset($context["U_MARK_FORUMS"]) ? $context["U_MARK_FORUMS"] : null)) {
            // line 8
            echo "\t<div class=\"action-bar compact\">
\t\t<a href=\"";
            // line 9
            echo (isset($context["U_MARK_FORUMS"]) ? $context["U_MARK_FORUMS"] : null);
            echo "\" class=\"mark-read rightside\" accesskey=\"m\" data-ajax=\"mark_forums_read\">";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("MARK_FORUMS_READ");
            echo "</a>
\t</div>
";
        }
        // line 12
        // line 13
        echo "
";
        // line 14
        if ((isset($context["BAN_AVRIL"]) ? $context["BAN_AVRIL"] : null)) {
            // line 15
            echo "<img height=\"200px\" width=\"1100px\" src=\"/img/avril.jpg\" />
";
        } elseif (        // line 16
(isset($context["BAN_MAI"]) ? $context["BAN_MAI"] : null)) {
            // line 17
            echo "<img height=\"200px\" width=\"1100px\" src=\"/img/mai.jpg\" />
";
        } elseif (        // line 18
(isset($context["BAN_JUIN"]) ? $context["BAN_JUIN"] : null)) {
            // line 19
            echo "<img height=\"200px\" width=\"1100px\" src=\"/img/juin.jpg\" />
";
        } elseif (        // line 20
(isset($context["BAN_JUILLET"]) ? $context["BAN_JUILLET"] : null)) {
            // line 21
            echo "<img height=\"200px\" width=\"1100px\" src=\"/img/juillet.png\" />
";
        } elseif (        // line 22
(isset($context["BAN_AOUT"]) ? $context["BAN_AOUT"] : null)) {
            // line 23
            echo "<img height=\"200px\" width=\"1100px\" src=\"/img/aout.jpg\" />
";
        } elseif (        // line 24
(isset($context["BAN_SEPTEMBRE"]) ? $context["BAN_SEPTEMBRE"] : null)) {
            // line 25
            echo "<img height=\"200px\" width=\"1100px\" src=\"/img/septembre.jpg\" />
";
        } elseif (        // line 26
(isset($context["BAN_OCTOBRE"]) ? $context["BAN_OCTOBRE"] : null)) {
            // line 27
            echo "<img height=\"200px\" width=\"1100px\" src=\"/img/octobre.png\" />
";
        } elseif (        // line 28
(isset($context["BAN_NOVEMBRE"]) ? $context["BAN_NOVEMBRE"] : null)) {
            // line 29
            echo "<img height=\"200px\" width=\"1100px\" src=\"/img/novembre.jpg\" />
";
        } elseif (        // line 30
(isset($context["BAN_DECEMBRE"]) ? $context["BAN_DECEMBRE"] : null)) {
            // line 31
            echo "<img height=\"200px\" width=\"1100px\" src=\"/img/decembre.jpg\" />
";
        }
        // line 33
        echo "
";
        // line 34
        $location = "forumlist_body.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("forumlist_body.html", "index_body.html", 34)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 35
        echo "
";
        // line 36
        // line 37
        echo "
";
        // line 38
        if (( !(isset($context["S_USER_LOGGED_IN"]) ? $context["S_USER_LOGGED_IN"] : null) &&  !(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null))) {
            // line 39
            echo "\t<form method=\"post\" action=\"";
            echo (isset($context["S_LOGIN_ACTION"]) ? $context["S_LOGIN_ACTION"] : null);
            echo "\" class=\"headerspace\">
\t<h3><a href=\"";
            // line 40
            echo (isset($context["U_LOGIN_LOGOUT"]) ? $context["U_LOGIN_LOGOUT"] : null);
            echo "\">";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN_LOGOUT");
            echo "</a>";
            if ((isset($context["S_REGISTER_ENABLED"]) ? $context["S_REGISTER_ENABLED"] : null)) {
                echo "&nbsp; &bull; &nbsp;<a href=\"";
                echo (isset($context["U_REGISTER"]) ? $context["U_REGISTER"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("REGISTER");
                echo "</a>";
            }
            echo "</h3>
\t\t<fieldset class=\"quick-login\">
\t\t\t<label for=\"username\"><span>";
            // line 42
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("USERNAME");
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
            echo "</span> <input type=\"text\" tabindex=\"1\" name=\"username\" id=\"username\" size=\"10\" class=\"inputbox\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("USERNAME");
            echo "\" /></label>
\t\t\t<label for=\"password\"><span>";
            // line 43
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PASSWORD");
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
            echo "</span> <input type=\"password\" tabindex=\"2\" name=\"password\" id=\"password\" size=\"10\" class=\"inputbox\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PASSWORD");
            echo "\" autocomplete=\"off\" /></label>
\t\t\t";
            // line 44
            if ((isset($context["U_SEND_PASSWORD"]) ? $context["U_SEND_PASSWORD"] : null)) {
                // line 45
                echo "\t\t\t\t<a href=\"";
                echo (isset($context["U_SEND_PASSWORD"]) ? $context["U_SEND_PASSWORD"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("FORGOT_PASS");
                echo "</a>
\t\t\t";
            }
            // line 47
            echo "\t\t\t";
            if ((isset($context["S_AUTOLOGIN_ENABLED"]) ? $context["S_AUTOLOGIN_ENABLED"] : null)) {
                // line 48
                echo "\t\t\t\t<span class=\"responsive-hide\">|</span> <label for=\"autologin\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOG_ME_IN");
                echo " <input type=\"checkbox\" tabindex=\"4\" name=\"autologin\" id=\"autologin\" /></label>
\t\t\t";
            }
            // line 50
            echo "\t\t\t<input type=\"submit\" tabindex=\"5\" name=\"login\" value=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN");
            echo "\" class=\"button2\" />
\t\t\t";
            // line 51
            echo (isset($context["S_LOGIN_REDIRECT"]) ? $context["S_LOGIN_REDIRECT"] : null);
            echo "
\t\t</fieldset>
\t</form>
";
        }
        // line 55
        echo "
";
        // line 56
        // line 57
        echo "
";
        // line 58
        if ((isset($context["S_DISPLAY_ONLINE_LIST"]) ? $context["S_DISPLAY_ONLINE_LIST"] : null)) {
            // line 59
            echo "\t<div class=\"stat-block online-list\">
\t\t";
            // line 60
            if ((isset($context["U_VIEWONLINE"]) ? $context["U_VIEWONLINE"] : null)) {
                echo "<h3><a href=\"";
                echo (isset($context["U_VIEWONLINE"]) ? $context["U_VIEWONLINE"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("WHO_IS_ONLINE");
                echo "</a></h3>";
            } else {
                echo "<h3>";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("WHO_IS_ONLINE");
                echo "</h3>";
            }
            // line 61
            echo "\t\t<p>
\t\t\t";
            // line 62
            // line 63
            echo "\t\t\t";
            echo (isset($context["TOTAL_USERS_ONLINE"]) ? $context["TOTAL_USERS_ONLINE"] : null);
            echo " (";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ONLINE_EXPLAIN");
            echo ")<br />";
            echo (isset($context["RECORD_USERS"]) ? $context["RECORD_USERS"] : null);
            echo "<br /> 
\t\t\t";
            // line 64
            if ((isset($context["U_VIEWONLINE"]) ? $context["U_VIEWONLINE"] : null)) {
                // line 65
                echo "\t\t\t\t<br />";
                echo (isset($context["LOGGED_IN_USER_LIST"]) ? $context["LOGGED_IN_USER_LIST"] : null);
                echo "
\t\t\t\t";
                // line 66
                if ((isset($context["LEGEND"]) ? $context["LEGEND"] : null)) {
                    echo "<br /><em>";
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LEGEND");
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
                    echo " ";
                    echo (isset($context["LEGEND"]) ? $context["LEGEND"] : null);
                    echo "</em>";
                }
                // line 67
                echo "\t\t\t";
            }
            // line 68
            echo "\t\t\t";
            // line 69
            echo "\t\t</p>
\t</div>
";
        }
        // line 72
        echo "
";
        // line 73
        // line 74
        echo "
";
        // line 75
        if ((isset($context["S_DISPLAY_BIRTHDAY_LIST"]) ? $context["S_DISPLAY_BIRTHDAY_LIST"] : null)) {
            // line 76
            echo "\t<div class=\"stat-block birthday-list\">
\t\t<h3>";
            // line 77
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("BIRTHDAYS");
            echo "</h3>
\t\t<p>
\t\t\t";
            // line 79
            // line 80
            echo "\t\t\t";
            if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "birthdays", array()))) {
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("CONGRATULATIONS");
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
                echo " <strong>";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "birthdays", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["birthdays"]) {
                    echo $this->getAttribute($context["birthdays"], "USERNAME", array());
                    if (($this->getAttribute($context["birthdays"], "AGE", array()) !== "")) {
                        echo " (";
                        echo $this->getAttribute($context["birthdays"], "AGE", array());
                        echo ")";
                    }
                    if ( !$this->getAttribute($context["birthdays"], "S_LAST_ROW", array())) {
                        echo ", ";
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['birthdays'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                echo "</strong>";
            } else {
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NO_BIRTHDAYS");
            }
            // line 81
            echo "\t\t\t";
            // line 82
            echo "\t\t</p>
\t</div>
";
        }
        // line 85
        echo "
";
        // line 86
        if ((isset($context["NEWEST_USER"]) ? $context["NEWEST_USER"] : null)) {
            // line 87
            echo "\t<div class=\"stat-block statistics\">
\t\t<h3>";
            // line 88
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("STATISTICS");
            echo "</h3>
\t\t<p>
\t\t\t";
            // line 90
            // line 91
            echo "\t\t\t";
            echo (isset($context["TOTAL_POSTS"]) ? $context["TOTAL_POSTS"] : null);
            echo " &bull; ";
            echo (isset($context["TOTAL_TOPICS"]) ? $context["TOTAL_TOPICS"] : null);
            echo " &bull; ";
            echo (isset($context["TOTAL_USERS"]) ? $context["TOTAL_USERS"] : null);
            echo " &bull; ";
            echo (isset($context["NEWEST_USER"]) ? $context["NEWEST_USER"] : null);
            echo "
\t\t\t";
            // line 92
            // line 93
            echo "\t\t</p>
\t</div>
";
        }
        // line 96
        echo "
";
        // line 97
        // line 98
        echo "
";
        // line 99
        $location = "overall_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_footer.html", "index_body.html", 99)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "index_body.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  362 => 99,  359 => 98,  358 => 97,  355 => 96,  350 => 93,  349 => 92,  338 => 91,  337 => 90,  332 => 88,  329 => 87,  327 => 86,  324 => 85,  319 => 82,  317 => 81,  291 => 80,  290 => 79,  285 => 77,  282 => 76,  280 => 75,  277 => 74,  276 => 73,  273 => 72,  268 => 69,  266 => 68,  263 => 67,  254 => 66,  249 => 65,  247 => 64,  238 => 63,  237 => 62,  234 => 61,  222 => 60,  219 => 59,  217 => 58,  214 => 57,  213 => 56,  210 => 55,  203 => 51,  198 => 50,  192 => 48,  189 => 47,  181 => 45,  179 => 44,  172 => 43,  165 => 42,  150 => 40,  145 => 39,  143 => 38,  140 => 37,  139 => 36,  136 => 35,  124 => 34,  121 => 33,  117 => 31,  115 => 30,  112 => 29,  110 => 28,  107 => 27,  105 => 26,  102 => 25,  100 => 24,  97 => 23,  95 => 22,  92 => 21,  90 => 20,  87 => 19,  85 => 18,  82 => 17,  80 => 16,  77 => 15,  75 => 14,  72 => 13,  71 => 12,  63 => 9,  60 => 8,  58 => 7,  57 => 6,  54 => 5,  48 => 4,  34 => 3,  31 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "index_body.html", "");
    }
}
