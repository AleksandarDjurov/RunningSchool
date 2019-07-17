import { NgModule } from '@angular/core';

import { FormsModule, ReactiveFormsModule} from '@angular/forms';
import { UIFormsModule } from 'app/main/inputforms/forms/forms.module';
import { UITechnicalFormModule } from 'app/main/inputforms/technical/technical.module';
import { UISchoolFormModule } from 'app/main/inputforms/school/schoolform.module';
import { UITrainerFormModule } from 'app/main/inputforms/trainer/trainer.module';
import { UIAthleteFormModule } from 'app/main/inputforms/athlete/athlete.module';

@NgModule({
    imports: [
        UIFormsModule,
        UISchoolFormModule,
        UITrainerFormModule,
        UITechnicalFormModule,
        UIAthleteFormModule,
        FormsModule,
        ReactiveFormsModule.withConfig({warnOnNgModelWithFormControl: 'never'})
    ]
})
export class MainFormsModule {}
