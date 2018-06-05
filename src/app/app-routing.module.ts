import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ShoppingListComponent } from './shopping-list/shopping-list.component';

const appRoutes: Routes = [
    { path: '', redirectTo: '/recipes', pathMatch: 'full'  },
    { path: 'shopping-list', component: ShoppingListComponent },
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
