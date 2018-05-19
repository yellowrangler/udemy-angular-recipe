import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Params, Router } from '@angular/router';

import { Recipe } from '../recipe.model';
import { RecipeService } from '../recipe.service';

@Component({
  selector: 'app-recipe-detail',
  templateUrl: './recipe-detail.component.html',
  styleUrls: ['./recipe-detail.component.css']
})
export class RecipeDetailComponent implements OnInit {
	recipe: Recipe;
    id: number;

	constructor(private recipeService: RecipeService,
                private route: ActivatedRoute,
                private router: Router) { }

	ngOnInit() {
        // const id = this.route.params['id'];
        this.route.params
            .subscribe(
                (params: Params) => {
                    this.id = +params['id'];
                    this.recipe = this.recipeService.getRecipe(this.id);
                }
            )
	}

	onAddToShoppingList() {
		this.recipeService.addIngredientsToShoppingList(this.recipe.ingredients);
	}

    onEditRecipe() {
        this.router.navigate(['edit'], { relativeTo: this.route });  // we already have the id on the url so adding edit works.
        // the next line actually rebuilds the entire line and works too
        // this.router.navigate(['../', this.id, 'edit'], { relativeTo: this.route });
    }

    onDeleteRecipe() {
        this.recipeService.deleteRecipe(this.id);
        this.router.navigate(['/recipes']);
    }

}
