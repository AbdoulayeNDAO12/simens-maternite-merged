<?php

namespace Maternite\Model;

use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Sql;

use Maternite\View\Helpers\DateHelper;

class AccouchementTable {
	
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
		
	}
	
	
	public function getAccouchement($id_cons) {
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->columns ( array (
				'*'
		) );
		$select->from ( array (
				'acc' => 'accouchement'
		) );
		$select->where ( array (
				'acc.id_cons' => $id_cons
		) );
		$select->order ( 'acc.id_accouchement ASC' );
		$stat = $sql->prepareStatementForSqlObject ( $select );
		$result = $stat->execute ()->current();
	
		return $result;
	}

	
	
	
    public function updateAccouchement($donnees,$id_grossesse) {
	
    	$Control = new DateHelper();
    	
		$this->tableGateway->delete ( array (
				'id_cons' => $donnees ['id_cons'], 
				
		) );
		$date_accouchement = $donnees['date_accouchement'];
		if($date_accouchement){ $date_accouchement = $Control->convertDateInAnglais($date_accouchement); }else{ $date_accouchement = null;}		
			
	
			$dataac = array (
					'id_cons' => $donnees ['id_cons'],
					'id_admission'=>$donnees['id_admission'],
					'id_grossesse'=>$id_grossesse,
					'id_type' => $donnees['type_accouchement'],					
 					'motif_type' => $donnees['motif_type'],
 					'date_accouchement' => $date_accouchement,
					'heure_accouchement' => $donnees['heure_accouchement'],
 					'delivrance' => $donnees['delivrance'],
					'ru' => $donnees['ru'],
					'quantite_hemo' => $donnees['quantite_hemo'],
					'hemoragie' => $donnees['hemoragie'],
					'ocytocique_per' => $donnees['ocytocique_per'],
					'ocytocique_post' => $donnees['ocytocique_post'],
					'antibiotique' => $donnees['antibiotique'],
					'anticonvulsant' => $donnees ['anticonvulsant'],
					'transfusion' => $donnees['transfusion'],
					'note_delivrance' => $donnees['note_delivrance'],
					'note_hemorragie' => $donnees['note_hemorragie'],
					'text_observation' => $donnees['text_observation'],
					'suite_de_couche' => $donnees['suite_de_couche'],
					'note_ocytocique' => $donnees['note_ocytocique'],
					'note_antibiotique' => $donnees['note_antibiotique'],
					'note_anticonv' => $donnees['note_anticonv'],
					'note_transfusion' => $donnees['note_transfusion'],				
			);//var_dump($dataac);exit();
	
			return $this->tableGateway->getLastInsertValue($this->tableGateway->insert ( $dataac ));
		
	}
	
	
	
public function addPrenomme($donne,$id_acc) {
		$db = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $db );
		$sQuery = $sql->insert ()->into ( 'prenomme_des_bb' )->values ( array (
				'id_accouchement' => $id_acc,
				'prenomme'  => $donne
		) );
		$requete = $sql->prepareStatementForSqlObject ( $sQuery );
		$requete->execute ();
	}
	
	public function getPrenomme($id_acc) {
		$adapter = $this->tableGateway->getAdapter ();
		$sql = new Sql ( $adapter );
		$select = $sql->select ();
		$select->columns ( array (
				'*'
		) );
		$select->from( array (
				'pre' => 'prenomme_des_bb'
		) )->join ( array (
				'acc' => 'accouchement'
		), 'pre.id_accouchement = acc.id_accouchement', array (
		
		));
		$select->where ( array (
				'pre.id_accouchement' => $id_acc
		) );
		$select->order ( 'pre.id_prenome ASC' );
		$stat = $sql->prepareStatementForSqlObject ( $select );
		$result = $stat->execute ()->current();
	
		return $result;
	}
	
	
	
	
	
	//donnee pour statistiques
	public function getNbPatientsAccouche(){
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('p' => 'patient'));
		$select->join(array('gro' => 'grossesse') ,'gro.id_patient = p.id_personne');
		$select->join(array('acc' => 'accouchement') ,'acc.id_grossesse = gro.id_grossesse');
		return $sql->prepareStatementForSqlObject($select)->execute()->count();
	}
	
	
	public function getNbPatientsAccCes(){
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('p' => 'patient'));
		$select->join(array('gro' => 'grossesse') ,'gro.id_patient = p.id_personne');
		$select->join(array('acc' => 'accouchement') ,'acc.id_grossesse = gro.id_grossesse');
		$select->join(array('t' => 'type_accouchement') ,'acc.id_type = t.id_type');
		$select->where(array('t.type_accouch' => 'Cesarienne'));
		return $sql->prepareStatementForSqlObject($select)->execute()->count();
	}
	
	
	
	public function getNbPatientsAccN(){
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('p' => 'patient'));
		$select->join(array('gro' => 'grossesse') ,'gro.id_patient = p.id_personne');
		$select->join(array('acc' => 'accouchement') ,'acc.id_grossesse = gro.id_grossesse');
		$select->join(array('t' => 'type_accouchement') ,'acc.id_type = t.id_type');
		$select->where(array('t.type_accouch' => 'Normal'));
		return $sql->prepareStatementForSqlObject($select)->execute()->count();
	}
	
	
	
	public function getNbPatientsAccF(){
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('p' => 'patient'));
		$select->join(array('gro' => 'grossesse') ,'gro.id_patient = p.id_personne');
		$select->join(array('acc' => 'accouchement') ,'acc.id_grossesse = gro.id_grossesse');
		$select->join(array('t' => 'type_accouchement') ,'acc.id_type = t.id_type');
		$select->where(array('t.type_accouch' => 'Forceps'));
		return $sql->prepareStatementForSqlObject($select)->execute()->count();
	}
	
	
	public function getNbPatientsAccV(){
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from(array('p' => 'patient'));
		$select->join(array('gro' => 'grossesse') ,'gro.id_patient = p.id_personne');
		$select->join(array('acc' => 'accouchement') ,'acc.id_grossesse = gro.id_grossesse');
		$select->join(array('t' => 'type_accouchement') ,'acc.id_type = t.id_type');
		$select->where(array('t.type_accouch' => 'Ventouse'));
		return $sql->prepareStatementForSqlObject($select)->execute()->count();
	}
	
	
}
