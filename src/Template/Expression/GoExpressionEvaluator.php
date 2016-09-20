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
    use Symfony\Component\Yaml\Exception\RuntimeException;
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
            } catch (\Exception $e) {
                throw new RuntimeException("{$e->getMessage()} on expression '$expression' in scope:\n". print_r ($scope, true), $e->getCode(), $e);
            }
        }
        
        public function yaml($expression, array $scope, $keyParse=false) {
            $type = Yaml::parse(trim($expression));
            if (is_array($type)) {
                foreach ($type as $key => $val) {
                    if ($keyParse)
                        $type[$key] = $this->eval($val, $scope);
                    else
                        $type[$key] = $val;
                }
                return $type;
            }
            return $this->eval($expression, $scope);
        }
    }