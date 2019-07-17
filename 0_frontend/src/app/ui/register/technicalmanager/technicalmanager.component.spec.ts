import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TechnicalmanagerComponent } from './technicalmanager.component';

describe('TechnicalmanagerComponent', () => {
  let component: TechnicalmanagerComponent;
  let fixture: ComponentFixture<TechnicalmanagerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TechnicalmanagerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TechnicalmanagerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
