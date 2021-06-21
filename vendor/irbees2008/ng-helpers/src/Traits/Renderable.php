<?php

namespace Plugins\Traits;

// Исключения.
use RuntimeException;

// Используем функции из пространства `Plugins`.
use function Plugins\setting;
use function Plugins\view;

trait Renderable
{
    /**
     * Список файлов шаблонов с полными путями, исключая имя шаблона.
     * @var array
     */
    protected $templatePath = [];

    /**
     * Определить все пути к файлам шаблонов.
     * @param  bool  $localsource  Расположение файлов шаблона в директории плагина.
     *      - `false` - шаблон сайта;
     *      - `true` - директория плагина.
     * @param  string|null  $skin
     * @return void
     */
    protected function defineTemplatePaths(bool $localsource, string $skin = null): void
    {
        $this->templatePath = locatePluginTemplates(
            $this->templates,
            $this->plugin,
            $localsource,
            $skin
        );
    }

    /**
     * Получить путь к файлу шаблона.
     * @param  string  $tpl
     * @return string
     *
     * @throws RuntimeException
     */
    protected function templatePath(string $tpl): string
    {
        if (empty($path = $this->templatePath[$tpl])) {
            throw new RuntimeException("Template [{$tpl}] is not define.");
        }

        return $path;
    }

    /**
     * Получить полный путь к файлу шаблона, включая имя шаблона.
     * @param  string  $filename
     * @return string
     */
    protected function template(string $filename): string
    {
        $path = $this->templatePath($filename);
        $file = $filename.'.tpl';

        return $path.$file;
    }

    /**
     * Получить полный путь к файлу шаблона, включая имя шаблона.
     * @param  string  $filename
     * @return string
     */
    protected function asset(string $filename): string
    {
        $path = $this->templatePath('url:'.$filename);
        $file = '/'.substr($filename, 1);

        return $path.$file;
    }

    /**
     * Выводит шаблон с заданным контекстом и возвращает его в виде строки.
     * @param  string  $name
     * @param  mixed  $args
     * @return string
     */
    protected function view(string $name, ...$args): string
    {

        // Определить все пути к шаблонам.
        $this->defineTemplatePaths(
            (bool) setting($this->plugin, 'localsource', 0)
        );

        return view($this->template($name), ...$args);
    }
}
