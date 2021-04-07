export default class Middleware{
    constructor(user){
        this.user = user.type;
    }

    admin(){
        return this.user === 1;
    }

    seller(){
        return this.user === 2;
    }

    customer(){
        return this.user === 3;
    }

    adminOrSeller(){
        if (this.user === 1 || this.user === 2){
            return true;
        }
    }

    adminOrUser(){
        if (this.user === 1 || this.user === 3){
            return true;
        }
    }

    authCheck(){
        if (this.user === 1 || this.user === 2 || this.user === 3){
            return true;
        }
    }
}