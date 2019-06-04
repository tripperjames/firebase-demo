<?php

namespace YOOtheme\Asset;

use YOOtheme\AssetInterface;

interface FilterInterface
{
    /**
     * Filter content callback.
     *
     * @param AssetInterface $asset
     */
    public function filterContent(AssetInterface $asset);
}
