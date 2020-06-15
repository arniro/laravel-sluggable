<?php

namespace Arniro\Sluggable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait Sluggable
{
    public static function bootSluggable()
    {
        static::saving(function (Model $model) {
            return $model->slug();
        });
    }

    /**
     * Set a slug for an attribute.
     *
     * @return $this
     */
    protected function slug()
    {
        if ($this->isTranslatable($this->getSluggable())) {
            return $this->slugWithTranslations();
        }

        if (! $this->slugShouldBeSet())
            return $this;

        return $this->setAttribute('slug', $this->getUniqueSlug());
    }

    /**
     * Generate a slug for a translatable attribute.
     *
     * @return $this
     */
    protected function slugWithTranslations()
    {
        foreach ($this->getSluggableLocales() as $locale) {
            $this->setTranslation('slug', $locale, $this->getUniqueSlug($locale));
        }

        return $this;
    }

    /**
     * Get an attribute key name that should be slugged.
     *
     * @return string
     */
    protected function getSluggable()
    {
        return 'name';
    }

    /**
     * Determine whether a slug should be updated or not.
     *
     * @return bool
     */
    protected function slugShouldBeSet()
    {
        if (! $this->exists) {
            return true;
        }

        return $this->isDirty($this->getSluggable());
    }

    /**
     * Determine whether an attribute is translatable or not.
     *
     * @param $attribute
     * @return bool
     */
    protected function isTranslatable($attribute)
    {
        return method_exists($this, 'isTranslatableAttribute')
            ? $this->isTranslatableAttribute($attribute)
            : false;
    }

    /**
     * Get sluggable attribute's locals that should be slugged.
     *
     * @return array
     */
    protected function getSluggableLocales()
    {
        $locales = array_keys($this->getTranslations($this->getSluggable()));

        if (! $this->exists) {
            return $locales;
        }

        $original = $this->fromJson(Arr::get($this->getOriginal(), $this->getSluggable(), '[]'));
        $dirty = $this->fromJson(Arr::get($this->getDirty(), $this->getSluggable(), '[]'));

        $changed = array_filter($dirty, function ($attribute, $locale) use ($original) {
            return $attribute !== Arr::get($original, $locale);
        }, ARRAY_FILTER_USE_BOTH);

        return array_keys($changed);
    }

    /**
     * Determine whether the given slug exists in the database.
     *
     * @param string $slug
     * @param string|null $locale
     * @return mixed
     */
    protected function slugExists($slug, $locale = null)
    {
        $column = 'slug';

        if ($locale) {
            $column .= "->$locale";
        }

        return $this->where($column, $slug)->exists();
    }

    /**
     * Generate a slug from the value of an attribute.
     *
     * @param string|null $locale
     * @return string
     */
    protected function getSlugged($locale = null)
    {
        $value = $locale
            ? $this->translate($this->getSluggable(), $locale)
            : $this->getAttribute($this->getSluggable());

        return Str::slug($value);
    }

    /**
     * Generate a unique slug.
     *
     * @param null $locale
     * @return string
     */
    protected function getUniqueSlug($locale = null)
    {
        $slug = $this->getSlugged($locale);

        $i = null;

        while ($this->slugExists($slug . $i, $locale)) {
            if ($i === null) {
                $slug .= '-';
                $i = 0;
            }

            $i++;
        }

        return $slug . $i;
    }
}
