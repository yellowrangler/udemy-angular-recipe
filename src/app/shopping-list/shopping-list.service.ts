import { EventEmitter } from '@angular/core';
import { Ingredient } from '../shared/ingredient.model';

export class ShoppingListService {
	ingredientsChanged = new EventEmitter<Ingredient[]>();

	private ingredients: Ingredient[] = [
		new Ingredient('Apples', 5),
		new Ingredient('Tomatoes', 10)
	];

	getIngredients() {
		return this.ingredients.slice();
	}

	addIngredient(ingredient: Ingredient) {
		this.ingredients.push(ingredient);
		this.ingredientsChanged.emit(this.ingredients.slice());
	}

	addIngredients(ingredients: Ingredient[]) {
		// could also do it this way
		// for (let ingredient of ingredients) {
		// 	this.addIngredient(ingredient);
		// }
		this.ingredients.push(...ingredients); // ... takes array passed in and breaks down into multiple entries for push
		this.ingredientsChanged.emit(this.ingredients.slice());
	}
	
}