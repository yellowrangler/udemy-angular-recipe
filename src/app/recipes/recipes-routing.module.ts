import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { RescipeStartComponent } from './rescipe-start/rescipe-start.component';
import { RecipesComponent } from './recipes.component';
import { RecipeDetailComponent } from './recipe-detail/recipe-detail.component';
import { RecipeEditComponent } from './recipe-edit/recipe-edit.component';

const RecipesRoutes: Routes = [
    { path: 'recipes', component: RecipesComponent,
        children: [
            { path: '',
              component: RescipeStartComponent },
            { path: 'new',
              component: RecipeEditComponent },
            { path: ':id',
              component: RecipeDetailComponent },
            { path: ':id/edit',
              component: RecipeEditComponent }
        ]}
  ];

  @NgModule({
  	imports: [ RouterModule.forChild(RecipesRoutes) ],
   	exports: [ RouterModule ]
  })

export class RecipesRoutingModule {

}
