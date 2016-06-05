<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.05.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\Mfw\Router;


class Templating implements TemplatingInterface
{
    /**
     * The application base path.
     * @var string
     */
    protected $appBasePath;

    /**
     * Creates a new Templating instance.
     *
     * @param string $appBasePath
     */
    public function __construct($appBasePath)
    {
        $this->appBasePath = $appBasePath;
    }

    /**
     * @return string
     */
    public function getAppBasePath()
    {
        return $this->appBasePath;
    }

    /**
     * @param string $appBasePath
     */
    public function setAppBasePath($appBasePath)
    {
        $this->appBasePath = $appBasePath;
    }

    /**
     * Gets the real path of the template.
     *
     * @param $template
     * @return string
     */
    protected function getTemplatePath($template)
    {
        return realpath(sprintf('%s/Resource/view/%s', $this->getAppBasePath(), $template));
    }

    /**
     * {@inheritdoc}
     */
    public function render($template, $parameters)
    {
        $templatePath = $this->getTemplatePath($template);
        if (!is_readable($templatePath)) {
            throw new \RuntimeException('Cannot read template file: ' . $templatePath);
        }
        
        $rendered = file_get_contents($templatePath);
        foreach ($parameters as $key => $value) {
            $pattern = sprintf('/{{\\$%s}}/', $key);
            $rendered = preg_replace($pattern, $value, $rendered);
        }

        return $rendered;
    }
}
