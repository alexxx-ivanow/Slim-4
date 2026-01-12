<?
namespace App\DTO;

class CreatePageDTO {
    protected readonly string $pagetitle;
    protected readonly string $alias;
    protected string $description = '';
    protected string $content = '';

    protected string $image = '';
    protected string $seo_title = '';
    protected string $seo_keywords = '';
    protected string $seo_description = '';
    public function __construct(Array $pageArray)
    {
        foreach($pageArray as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getPage()
    {
        return [
            'pagetitle' => $this->pagetitle,
            'alias' => $this->alias,
            'description' => $this->description,
            'content' => $this->content,
            'image' => $this->image,
            'seo_title' => $this->seo_title,
            'seo_keywords' => $this->seo_keywords,
            'seo_description' => $this->seo_description,
        ];
    }
}