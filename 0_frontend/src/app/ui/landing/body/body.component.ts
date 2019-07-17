import { Component, ViewChild , OnInit, ElementRef } from '@angular/core';
import { Router } from '@angular/router';
//import { CarouselComponent } from 'angular-bootstrap-md'

@Component({
  selector: 'app-body',
  templateUrl: './body.component.html',
  styleUrls: ['./body.component.css']
})
export class BodyComponent implements OnInit {
  @ViewChild('carousel') private carousel : any;
  
  images1 = `frontend/assets/img/scenery/image1.jpg`;
  images2 = `frontend/assets/img/scenery/image4.jpg`;
  images3 = `frontend/assets/img/scenery/image6.jpg`;

  constructor(
    private router: Router) { }

  ngOnInit() {
    this.OnStartPlayCarousel();
  }

  OnContacUS() {
    this.router.navigate(['/contactus']);
  }

  OnStartPlayCarousel() {
    //this.carousel({ interval: 1});
  }
}