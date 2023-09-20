<?php

namespace App\Admin\Controllers;

use App\Models\Article;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use App\Models\ArticleType;

class ArticleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Makale';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Article());
        $grid ->column('title',__('Başlık'));
        $grid->column('article.title','Kategori');
        $grid->column('sub_title',__('Alt Başlık'));
        $grid ->column('description',__('Açıklama'))->display(function($val){
             return substr($val,0,300);
        });
        $grid ->column('released','Yayınlandı')->bool();
        $grid->column('thumbnail',__('Küçük Resim'))->image('','60','60');
        $grid->column('author',__('Yazar'));
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('title',__('Başlık'));
            $filter->like('article.title',__('Kategori'));
        });

        if(Admin::user()->name != "Administrator"){

            $grid->disableActions();
        }

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Article::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Article());
        $form->select('type_id',__('Kategori'))->options((new ArticleType())::selectOptions());
        $form -> text('title',__('Başlık'))->required();
        $form -> text('sub_title',__('Alt Başlık'));
        $form -> image('thumbnail',__('Küçük Resim'));
        $form -> ckeditor('description',__('Açıklama'))->required();
        $states = [
            'on' =>['value'=>1,'text'=>'yayınla'],
            'off'=>['value'=>0,'text'=>'taslak']
        ];
        $form -> switch('released',__('Yayınla'))->states($states);
        $form -> text('author',__('Yazar'))->value(Admin::user()->name)->required()->readonly();
        



        return $form;
    }
}
