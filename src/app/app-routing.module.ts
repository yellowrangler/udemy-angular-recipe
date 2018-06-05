import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ShoppingListComponent } from './shopping-list/shopping-list.component';
import { HomeComponent } from './home/home.component';

const appRoutes: Routes = [
    { path: '', component: HomeComponent  },
    { path: 'recipes', loadChildren: './recipes/recipes.module#RecipesModule' },
    { path: 'shopping-list', component: ShoppingListComponent }
  ];

@NgModule({
	imports: [
	    // RouterModule.forRoot(appRoutes, { useHash: true })   Use this hash for old browsers or old web servers
      RouterModule.forRoot(appRoutes)
	  ],
 	exports: [
  		RouterModule
  	]
})

export class AppRoutingModule {

}
