<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 29.07.16
 * Time: 01:54
 */

    namespace Html5\Template\Expression;


    use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
    use Symfony\Component\ExpressionLanguage\SyntaxError;
    use Symfony\Component\Yaml\Yaml;

    class GoExpressionEvaluator {

        private $language;

        public function __construct() {
            $this->language = new ExpressionLanguage();
        }


        public function register($functionName, callable $callback) {
            $this->language->register($functionName, function () { throw new \InvalidArgumentException('compile not supported'); }, $callback);
        }


        public function eval($expression, array $scope) {
            try {
                return $this->language->evaluate($expression, $scope);
            } catch (SyntaxError $e) {
                return null;
            }
        }
        
        public function yaml($expression, array $scope) {
            $type = Yaml::parse($expression);
            if (is_array($type)) {
                foreach ($type as $key => $val) {
                    $type[$key] = $this->eval($val, $scope);
                }
                return $type;
            }
            return $this->eval($expression, $scope);
        }
    }