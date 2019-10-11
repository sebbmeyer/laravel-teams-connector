<?php

namespace Sebbmyr\LaravelTeams\Cards;

use Sebbmyr\Teams\TeamsConnectorInterface;

/**
 * Sebbmyr\LaravelTeams\Cards\CustomCard
 */
class CustomCard implements TeamsConnectorInterface
{
    /**
     * Theme color
     * @var string
     */
    private $color;

    /**
     * Summary
     * @var string
     */
    private $summary;

    /**
     * Title
     * @var string
     */
    private $title;

    /**
     * Generic text
     * @var string
     */
    private $text;

    /**
     * Action Buttons
     * @var array
     */
    private $potentialAction;

    /**
     * Sections
     * @var array
     */
    private $sections;

    /**
     * CustomCard constructor.
     * @param null $title
     * @param null $text
     */
    public function __construct($title = null, $text = null)
    {
        $this->title = $title ?: '';
        $this->text = $text ?: '';
    }

    /**
     * Formats data for API call
     */
    public function getMessage()
    {
        $message = collect([
            "@type" => "MessageCard",
            "@context" => "http://schema.org/extensions",
        ]);
        if (isset($this->summary)) {
            $message->put('summary', $this->summary);
        }
        if (isset($this->title)) {
            $message->put('title', $this->title);
        }
        if (isset($this->text)) {
            $message->put('text', $this->text);
        }
        if (isset($this->color)) {
            $message->put('themeColor', $this->color);
        }

        if (isset($this->sections)) {
            $message->put('sections', $this->sections);
        }
        if (isset($this->potentialAction)) {
            $message->put('potentialAction', $this->potentialAction);
        }
        return $message;
    }

    /**
     * Sets Card Title
     * @param string $title
     * @return CustomCard
     */
    public function addTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Sets Card Text
     * @param string $text
     * @return CustomCard
     */
    public function addText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Sets Card Summary
     * @param string $summary
     * @return CustomCard
     */
    public function addSummary(string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * Sets Card Color
     * @param string $color
     * @return CustomCard
     */
    public function addColor(string $color): self
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Add activity section to card
     * @param string $text
     * @param string|null $title
     * @param string|null $image
     * @return CustomCard
     */
    public function addActivity(string $text, ?string $title = null, ?string $image = null): self
    {
        $activity = collect(['activityTitle' => $title]);
        if ($text !== null) {
            $activity->put('activityText', $text);
        }
        if ($image !== null) {
            $activity->put('activityImage', $image);
        }
        $this->sections[] = $activity;
        return $this;
    }

    /**
     * Add text section to card
     * @param string $title
     * @param array|null $array
     * @return CustomCard
     */
    public function addFactsText(string $title, ?array $array = null): self
    {
        $section = collect(['title' => $title]);
        if (!is_null($array)) {
            $facts = collect();
            foreach ($array as $arr) {
                $facts->push(['name' => $arr]);
            }
            $section->put('facts', $facts);
        }
        $this->sections[] = $section;
        return $this;
    }


    /**
     * Add facts section to card
     * @param string $title
     * @param array $array
     * @return CustomCard
     */
    public function addFacts(string $title, array $array): self
    {
        $section = collect(['title' => $title]);
        $facts = collect();
        foreach ($array as $name => $value) {
            $facts->push(['name' => $name, 'value' => $value]);
        }
        $section->put('facts', $facts);
        $this->sections[] = $section;
        return $this;
    }


    /**
     * Add single image to card
     * @param string $title
     * @param string $image
     * @return CustomCard
     */
    public function addImage(string $title, string $image): self
    {
        $this->addImages($title, [$image]);
        return $this;
    }

    /**
     * Add images to card
     * @param string $title
     * @param array $images
     * @return CustomCard
     */
    public function addImages(string $title, array $images): self
    {
        $section = collect(['title' => $title]);
        $sectionImage = collect();
        foreach ($images as $image) {
            $sectionImage->put('image', $image);
        }
        $section->push($sectionImage);
        $this->sections[] = $section;
        return $this;
    }

    /**
     * Add action button to card
     * @param string $text
     * @param string $url
     * @return CustomCard
     */
    public function addAction(string $text, string $url): self
    {
        $this->potentialAction[] = [
            '@context' => 'http://schema.org',
            '@type' => 'ViewAction',
            'name' => $text,
            'target' => [$url],
        ];
        return $this;
    }
}
