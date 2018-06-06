import { Component } from '@angular/core';
import { Response } from '@angular/http';

import { DataStorageService } from '../../shared/data-storage.service';

@Component({
	selector: 'app-header',
	templateUrl: './header.component.html'
})

export class HeaderComponent {
	constructor(private dataStorageService: DataStorageService) {}

	onSaveData() {
		this.dataStorageService.storeRecipes()
		.subscribe(
            (response) => console.log(response),
            (error) => console.log(error)
        );
	}

	onGetData() {
		this.dataStorageService.getRecipes();
	}

}
