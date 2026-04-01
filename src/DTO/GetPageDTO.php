<?
namespace App\DTO;

class GetPageDTO {
    protected readonly int $id;
    protected readonly string $pagetitle;
    protected readonly string $alias;
    protected readonly string | null $description;
    protected readonly string | null $content;

    protected readonly string $image;
    protected readonly string $seo_title;
    protected readonly string $seo_keywords;
    protected readonly string $seo_description;
    public function __construct(Array $pageArray
        /*int $id,
        string $pagetitle,
        string $alias,
        string $description,
        string $content,
        string $image,
        string $seo_title,
        string $seo_keywords,
        string $seo_description,*/
    )
    {
        $this->id  = $pageArray['id'];
        $this->pagetitle  = $pageArray['pagetitle'];
        $this->alias  = $pageArray['alias'];
        $this->description  = $pageArray['description'];
        $this->content  = $pageArray['content'];
        $this->image  = $pageArray['image'];
        $this->seo_title  = $pageArray['seo_title'];
        $this->seo_keywords  = $pageArray['seo_keywords'];
        $this->seo_description  = $pageArray['seo_description'];
    }

    public function getPage()
    {
        return [
            'id' => $this->id,
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