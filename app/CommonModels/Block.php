<?php

namespace App\CommonModels;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    public static function getBlocksByDistrictId($id){
        return Block::where([
            ['district_id','=',$id]
        ])->select('id', 'block_name')
            ->get();
    }
	
	public static function getBlockIdByAnchalikId($id){
		return Block::where([
					['anchalik_id','=',$id],
				])->select('blocks.id')->first();
		
		
	}
	
	public static function getBlockNameById($id){
		return Block::where([
					['id','=',$id],
				])->select('id', 'block_name')->first();
		
		
	}

}
