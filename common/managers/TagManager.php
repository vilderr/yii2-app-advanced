<?php

namespace common\managers;

use common\models\Tag;
use common\repo\TagRepository as Repository;
use common\collects\TagCollection as Collection;

/**
 * Class TagManager
 * @package common\managers
 */
class TagManager
{
    private $_repository;

    /**
     * TagManager constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->_repository = $repository;
    }

    /**
     * @param Collection $collection
     *
     * @return Tag
     */
    public function create(Collection $collection)
    {
        $tag = Tag::create($collection);
        $this->_repository->save($tag);

        return $tag;
    }

    /**
     * @param Tag        $tag
     * @param Collection $collection
     */
    public function edit(Tag $tag, Collection $collection)
    {
        $tag->edit($collection);
        $this->_repository->save($tag);
    }

    /**
     * @param Tag $tag
     */
    public function remove(Tag $tag)
    {
        $this->_repository->delete($tag);
    }
}