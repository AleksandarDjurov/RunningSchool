import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SchooladminComponent } from './schooladmin.component';

describe('SchooladminComponent', () => {
  let component: SchooladminComponent;
  let fixture: ComponentFixture<SchooladminComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SchooladminComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SchooladminComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
