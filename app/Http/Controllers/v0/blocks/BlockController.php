<?php

namespace App\Http\Controllers\v0\blocks;

use App\Http\Controllers\ApiController;
use App\Models\v0\block\Block;
use App\Models\v0\block\BlockTransformer;
use App\Serializers\ArraySerializer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class BlockController extends ApiController {

    public function index(Manager $fractal, BlockTransformer $blockTransformer) {
        $blocks = Block::orderBy('time', 'desc')->paginate(10);

        foreach ($blocks as $block) {
            $block->miner_reward = $block->transactions[0]->outputs[0]->value;
        }

        $collection = new Collection($blocks, $blockTransformer);

        $collection->setPaginator(new IlluminatePaginatorAdapter($blocks));

        $data = $fractal->createData($collection)->toArray();

        return $this->respond($data);
    }

    public function show($hash, Manager $fractal, BlockTransformer $blockTransformer) {
        $fractal->parseIncludes('transactions.inputs,transactions.outputs');

        $block = Block::where('hash', $hash)->get()->first();

        if (is_null($block)) {
            $block = is_numeric($hash) ? Block::where('height', $hash)->get()->first() : null;

            if (is_null($block)) {
                return $this->setStatusCode(404)->respond(['errors' => ['invalid-block']]);
            }
        }

        $block->miner_reward = $block->transactions[0]->outputs[0]->value;

        $item = new Item($block, $blockTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

}