<?php

/**
 * AuditController
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Brett O'Donnell <cornernote@gmail.com>, Zain Ul abidin <zainengineer@gmail.com>
 * @link https://github.com/cornernote/yii-dressing
 * @license http://www.gnu.org/copyleft/gpl.html
 */
class AuditController extends YdWebController
{

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'view', 'preserve', 'unPreserve'),
                'roles' => array('admin'),
            ),
            array('deny', 'users' => array('*')),
        );
    }

    /**
     * Lists all Audits.
     */
    public function actionIndex()
    {
        $audit = new YdAudit('search');
        if (!empty($_GET['YdAudit']))
            $audit->attributes = $_GET['YdAudit'];
        $urlManager = app()->getUrlManager();
        $urlManager->setUrlFormat('get');
        $this->render('dressing.views.audit.index', array(
            'audit' => $audit,
        ));
    }


    /**
     * Displays a particular Audit.
     * @param integer $id the ID of the Audit to be displayed
     */
    public function actionView($id)
    {
        $audit = $this->loadModel($id, 'YdAudit');
        $this->render('dressing.views.audit.view', array(
            'audit' => $audit,
        ));
    }

    /**
     * Preserves a particular Audit.
     * @param integer $id the ID of the Audit to be displayed
     * @param int $status
     * @return void
     */
    public function actionPreserve($id, $status = 1)
    {
        $id = (int)$id;
        $status = (int)$status;
        $audit = $this->loadModel($id, 'YdAudit');
        //$sql = "UPDATE " . Audit::model()->tableName() . " SET preserve = $status WHERE id = $id";
        //app()->db->createCommand($sql)->execute();
        $audit->preserve = $status;
        $audit->save(false);
        $this->redirect($audit->getUrl(), true);
    }

}
