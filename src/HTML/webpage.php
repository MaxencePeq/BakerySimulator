<?php

declare(strict_types=1);

namespace Maxence\BakerySimulator\HTML;

/**
 *
 */
class webpage
{
    /**
     * @var string
     */
    private string $head;
    /**
     * @var string
     */
    private string $title;
    /**
     * @var string
     */
    private string $body;

    /**
     * @param string $title
     */
    public function __construct(string $title = "")
    {
        $this->title = $title;
        $this->head = "";
        $this->body = "";
    }

    /**
     * @return string
     */
    public function getHead(): string
    {
        return $this->head;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $content
     * @return void
     */
    public function appendToHead(string $content): void
    {
        $this->head = $this->head . $content;
    }

    /**
     * @param string $css
     * @return void
     */
    public function appendCss(string $css): void
    {
        $this->appendToHead("<style>\n$css</style>\n");
    }

    /**
     * @param string $url
     * @return void
     */
    public function appendCssUrl(string $url): void
    {
        $this->appendToHead("<link rel='stylesheet' href='$url'>\n");
    }

    /**
     * @param string $js
     * @return void
     */
    public function appendJs(string $js): void
    {
        $this->appendToHead("<script>\n$js\n</script>\n");
    }

    /**
     * @param string $url
     * @return void
     */
    public function appendJsUrl(string $url): void
    {
        $this->appendToHead("<script src='$url'></script>\n");
    }

    /**
     * @param string $content
     * @return void
     */
    public function appendContent(string $content): void
    {
        $this->body = $this->body . $content;
    }

    /**
     * @return string
     */
    public function toHTML(): string
    {
        return "<!DOCTYPE html>\n
        <html lang='fr'>\n
            <head>\n
                <meta charset='UTF-8'>\n
                <title>{$this->title}</title>\n
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n
                {$this->head}\n
            </head>\n
            <body>\n
                {$this->body}\n
            </body>\n
        </html>";
    }

    /**
     * @param string $string
     * @return string
     */
    public function escapeString(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML401 | ENT_XML1 | ENT_XHTML | ENT_HTML5);
    }

    /**
     * @return string
     */
    public function getLastModification(): string
    {
        return "Dernière modification efféctué le . :" . date("d/m/Y à H:i:s", getlastmod());
    }
}
