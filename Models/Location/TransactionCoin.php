<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Location\Notifi;
use Carbon\Carbon;
class TransactionCoin extends Base
{
	//
	protected $table = 'transaction_coin';
	protected $error;
	protected $transfer = null;
	

	public function transfer($from,$to,$adjust,$description,$fee=0){
		$type = 'transfer';
		if($description==''){
			$description = trans('transaction.transfer_description',[
				'from'	=> $from->full_name,
				'adjust'	=> $adjust,
				'to'	=> $to->full_name,
			]);
		}
		$before_from = $from->coin?$from->coin:0;
		$after_from = $before_from - $adjust - $fee;
		$from_client =  $from->id;

		$before_to = $to->coin?$to->coin:0;
		$after_to = $before_to + $adjust;
		$to_client =  $to->id;

		if($after_from < 0){
			$this->error = new \Exception(trans('transaction.not_enough_coin'));
			return false;
		}

		DB::beginTransaction();
		try {
			//Update from
			DB::table('clients')
				->where('id', $from->id)
				->update(['coin' => $after_from]);

			//Update To
			DB::table('clients')
				->where('id', $to->id)
				->update(['coin' => $after_to]);

			//Add info transaction
			$this->transfer = DB::table('transaction_coin')
				->insertGetId([
						'type'        => $type,
						'before_from' => $before_from,
						'adjust'      => $adjust,    
						'fee'         => $fee, 
						'after_from'  => $after_from,
						'before_to'   => $before_to,
						'after_to'    => $after_to,  
						'description' => $description,
						'from_client' => $from_client,
						'to_client'   => $to_client,
						'created_at'	=> Carbon::now()
					]);
			DB::commit();
			$notifi = new Notifi();
			$notifi->createNotifiUserByTemplate('transaction.transfer_description',$from->id,[
				'from'	=> $from->full_name,
				'adjust'	=> $adjust,
				'to'	=> $to->full_name,
			]);

			$notifi2 = new Notifi();
			$notifi2->createNotifiUserByTemplate('transaction.transfer_description',$to->id,[
				'from'	=> $from->full_name,
				'adjust'	=> $adjust,
				'to'	=> $to->full_name,
			]);
			return true;
		} catch (\Exception $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		} catch (\Throwable $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		}
	}

	public function transfer_rollback($id_transaction){
		$transaction = self::where('id',$id_transaction)
												->where('rollback',0)
												->first();
		DB::beginTransaction();
		try {
			//Update from
			DB::table('clients')
				->where('id', $transaction->from_client)
				->update(['coin' => $transaction->before_from]);

			//Update To
			DB::table('clients')
				->where('id', $transaction->to_client)
				->update(['coin' => $transaction->before_to]);

			//Add info transaction
			DB::table('transaction_coin')
				->where('id', $id_transaction)
				->update([
						'rollback' => 1,
						'updated_at'	=> Carbon::now()
					]);
			DB::commit();
			return true;
		} catch (\Exception $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		} catch (\Throwable $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		}
	}

	public function pay($user,$adjust,$description,$fee=0){
		$type = 'pay';
		$before_from = $user->coin?$user->coin:0;
		$after_from = $before_from - $adjust - $fee;
		$from_client =  $user->id;

		$before_to = 0;
		$after_to = 0;
		$to_client =  0;

		if($after_from < 0){
			$this->error = new \Exception(trans('transaction.not_enough_coin'));
			return false;
		}

		DB::beginTransaction();
		try {
			//Update from
			DB::table('clients')
				->where('id', $user->id)
				->update(['coin' => $after_from]);

			//Add info transaction
			$this->transfer = DB::table('transaction_coin')
				->insertGetId([
						'type'        => $type,
						'before_from' => $before_from,
						'adjust'      => $adjust,    
						'fee'         => $fee, 
						'after_from'  => $after_from,
						'before_to'   => $before_to,
						'after_to'    => $after_to,  
						'description' => $description,
						'from_client' => $from_client,
						'to_client'   => $to_client,
						'created_at'	=> Carbon::now()
					]);
			DB::commit();
			return true;
		} catch (\Exception $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		} catch (\Throwable $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		}
	}

	public function pay_rollback($id_transaction){
		$transaction = self::where('id',$id_transaction)
												->where('rollback',0)
												->first();
		DB::beginTransaction();
		try {
			//Update from
			DB::table('clients')
				->where('id', $transaction->from_client)
				->update(['coin' => $transaction->before_from]);

			//Add info transaction
			DB::table('transaction_coin')
				->where('id', $id_transaction)
				->update([
						'rollback' => 1,
						'updated_at'	=> Carbon::now()
					]);
			DB::commit();
			return true;
		} catch (\Exception $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		} catch (\Throwable $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		}
	}

	public function bonus($user,$adjust,$description,$fee=0){
		$type = 'bonus';
		$before_from = 0;
		$after_from = 0;
		$from_client =  0;

		$before_to = $user->coin?$user->coin:0;
		$after_to = $before_to + $adjust;
		$to_client =  $user->id;

		DB::beginTransaction();
		try {
			//Update To
			DB::table('clients')
				->where('id', $user->id)
				->update(['coin' => $after_to]);

			//Add info transaction
			$this->transfer = DB::table('transaction_coin')
				->insertGetId([
						'type'        => $type,
						'before_from' => $before_from,
						'adjust'      => $adjust,    
						'fee'         => $fee, 
						'after_from'  => $after_from,
						'before_to'   => $before_to,
						'after_to'    => $after_to,  
						'description' => $description,
						'from_client' => $from_client,
						'to_client'   => $to_client,
						'created_at'	=> Carbon::now()
					]);
			DB::commit();
			return true;
		} catch (\Exception $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		} catch (\Throwable $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		}
	}

	public function bonus_rollback($id_transaction){
		$transaction = self::where('id',$id_transaction)
												->where('rollback',0)
												->first();
		DB::beginTransaction();
		try {
			//Update To
			DB::table('clients')
				->where('id', $transaction->to_client)
				->update(['coin' => $transaction->before_to]);

			//Add info transaction
			DB::table('transaction_coin')
				->where('id', $id_transaction)
				->update([
						'rollback' => 1,
						'updated_at'	=> Carbon::now()
					]);
			DB::commit();
			return true;
		} catch (\Exception $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		} catch (\Throwable $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		}
	}

	public function payback($user,$adjust,$description,$fee=0){
		$type = 'payback';
		$before_from = 0;
		$after_from = 0;
		$from_client =  0;

		$before_to = $user->coin?$user->coin:0;
		$after_to = $before_to + $adjust;
		$to_client =  $user->id;

		DB::beginTransaction();
		try {
			//Update To
			DB::table('clients')
				->where('id', $user->id)
				->update(['coin' => $after_to]);

			//Add info transaction
			$this->transfer = DB::table('transaction_coin')
				->insertGetId([
						'type'        => $type,
						'before_from' => $before_from,
						'adjust'      => $adjust,    
						'fee'         => $fee, 
						'after_from'  => $after_from,
						'before_to'   => $before_to,
						'after_to'    => $after_to,  
						'description' => $description,
						'from_client' => $from_client,
						'to_client'   => $to_client,
						'created_at'	=> Carbon::now()
					]);
			DB::commit();
			return true;
		} catch (\Exception $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		} catch (\Throwable $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		}
	}

	public function payback_rollback($id_transaction){
		$transaction = self::where('id',$id_transaction)
												->where('rollback',0)
												->first();
		DB::beginTransaction();
		try {
			//Update To
			DB::table('clients')
				->where('id', $transaction->to_client)
				->update(['coin' => $transaction->before_to]);

			//Add info transaction
			DB::table('transaction_coin')
				->where('id', $id_transaction)
				->update([
						'rollback' => 1,
						'updated_at'	=> Carbon::now()
					]);
			DB::commit();
			return true;
		} catch (\Exception $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		} catch (\Throwable $e) {
			DB::rollback();
			$this->error = $e;
			return false;
		}
	}


	public function getError(){
		return $this->error;
	}

	public function getTransfer(){
		return $this->transfer;
	}

	public function _from_client()
	{
		return $this->belongsTo('App\Models\Location\Client', 'from_client', 'id');
	}

	public function _to_client()
	{
		return $this->belongsTo('App\Models\Location\Client', 'to_client', 'id');
	}

}
