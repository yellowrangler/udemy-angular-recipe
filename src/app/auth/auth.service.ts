import { Injectable } from '@angular/core';
import  { Http, Response } from '@angular/http';
import 'rxjs/Rx';
import { Observable } from 'rxjs/Observable';

@Injectable()
export class AuthService {
    private myhost: string = "http://207.172.210.234/rest/";
    private SignupUser: string = "signup.php";
    private SigninUser: string = "signin.php";

    constructor(private http: Http) {}

    signupUser(email: string, password:string) {

        this.http.post(this.myhost+this.SignupUser, { 'email': email, 'password' : password } )
        .subscribe(
            (response) => console.log(response),
            (error) => console.log(error)
        )
    }

    signinUser(email: string, password:string) {

        this.http.post(this.myhost+this.SigninUser, { 'email': email, 'password' : password } )
        .subscribe(
            (response) => console.log(response),
            (error) => console.log(error)
        )
    }
}
