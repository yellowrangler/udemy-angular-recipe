import { Injectable } from '@angular/core';
import  { Http, Response } from '@angular/http';
import 'rxjs/Rx';

import { RecipeService } from '../recipes/recipe.service';
import { Recipe } from '../recipes/recipe.model';

@Injectable()
export class DataStorageService {
    private myhost: string = "http://207.172.210.234/rest/";
    private saveRecipesInfo: string = "saverecipesinfo.php";
    private getRecipesInfo: string = "getrecipesinfo.php";

    constructor(private http: Http,
                private recipeService: RecipeService) {}

    storeRecipes() {
        return this.http.post(this.myhost+this.saveRecipesInfo,
                                this.recipeService.getRecipes());
    }

    getRecipes() {
        this.http.get(this.myhost+this.getRecipesInfo)
        .map(
            (response: Response) => {
                const recipes: Recipe[] = response.json();
                for (let recipe of recipes) {
                    if (!recipe['ingredients']) {
                        recipe['ingredients'] = [];
                    }
                }

                return recipes;
            }
        )
        .subscribe(
            (recipes: Recipe[]) => {

                this.recipeService.setRecipes(recipes);
            }
        );
    }
}
