( function ( $ ) {
	class buyerRequestDetails {
		constructor() {
      var config = siteConfig?.config ?? {
        playlist: {icon: true, title: true, download: true}, attachments: {column: 2, download: true}
      };
      this.config = {
        playlist: {
          icon: config.playlist.icon,
          title: config.playlist.title,
          download: config.playlist.download
        },
        attachments: {
          column: config.attachments.column,
          download: config.attachments.download
        }
      };
      this.i18n = siteConfig?.i18n ?? {
        playlist: 'Playlist',
        attachments: 'Attachments',
        lesson_playlist: 'Lesson Playlist',
        lesson_attachments: 'Lesson Attachments',
        size: 'Size'
      };
      this.attachments = siteConfig?.attachments ?? [];

      // this.ajaxUrl = siteConfig?.ajaxUrl ?? '';
      // alert( 'is Started' );
      this.init();
		}
    init() {

      if( typeof window.fwpCustomVideoPlayer === 'undefined' ) {
        window.fwpCustomVideoPlayer = new Plyr( '#fwp-custom-video-player', {
					title: 'Course video lesson'
				} );
      }

      this.addbuttons = true;this.currentVideo = siteConfig?.currentVideo ?? 0;
      this.player = ( typeof window.fwpCustomVideoPlayer !== 'undefined' ) ? window.fwpCustomVideoPlayer : false; // plyr.setup( ".js-player" );
      this.lists = ( typeof window.fwpCustomVideoPlayList !== 'undefined' ) ? window.fwpCustomVideoPlayList : [];

      // if( this.lists[0] ) {this.player.source = this.lists[0];}
      this.regSwitch();
      this.tabs();
      this.switchVideo( this.currentVideo );
      this.eventLoader();
      // this.player();
      // this.scroll();
    }
    regSwitch() {
      window.fwpCustomVideoPlayer.switch = function( i ) {
        if( i == 'next' ) {i = ( window.fwpCustomVideoPlayer.currentVideo + 1 );}
        if( i == 'prev' ) {i = ( window.fwpCustomVideoPlayer.currentVideo - 1 );}
        if( typeof window.fwpCustomVideoPlayList[ i ] !== 'undefined' ) {
          window.fwpCustomVideoPlayer.source = window.fwpCustomVideoPlayList[ i ];
          window.fwpCustomVideoPlayer.currentVideo = i;
          var e = document.querySelectorAll( '#tutor-course-spotlight-playlist .tutor-course-attachments > div' );
          if( typeof e[i] !== 'undefined' ) {
            e.forEach( function( el, ie ) {
              el.removeAttribute( 'is-playing' );
            } );
            e[i].setAttribute( 'is-playing', true );
          }
        }
      }
    }
    eventLoader() {
      const thisClass = this;
      var listners = [
        "ended", "progress", "stalled", "playing", "waiting", "canplay", "canplaythrough", "loadstart", "loadeddata", "loadedmetadata", "timeupdate", "volumechange", "play", "pause", "error", "seeking", "seeked", "emptied", "ratechange", "cuechange", "download", "enterfullscreen", "exitfullscreen", "captionsenabled", "captionsdisabled", "languagechange", "controlshidden", "controlsshown", "ready", "statechange", "qualitychange", "adsloaded", "adscontentpause", "adscontentresume", "adstarted", "adsmidpoint", "adscomplete", "adsallcomplete", "adsimpression", "adsclick"
      ];
      // this.player.on( 'progress', thisClass.onProgress );
      this.player.on( 'ended', thisClass.onEnded );
      // thisClass.player.on( 'ended', function( e = false ) {
      //   thisClass.nextVideo();
      // } );
    }
    onProgress( e = false ) {
      console.log( e.timeStamp );
    }
    onEnded( e = false ) {
      window.fwpCustomVideoPlayer.switch( 'next' );
      // console.log( e );
    }
    onExampleEvent( e = false, f = false, g = false ) {
      console.log( [ e, f, g ] );
    }

    switchVideo( i ) {
      // alert( 'video switched' );
      if( this.player ) {
        window.fwpCustomVideoPlayer.switch( i );
        // this.player.source = this.lists[ i ];
        this.currentVideo = i;
      } else {
        console.log( 'Someting ( Player ) is not identified' );
      }
    }
    nextVideo() {
      window.fwpCustomVideoPlayer.switch( 'next' );
      // if( this.currentVideo >= this.lists.length ) {return;}
      // this.switchVideo( ( this.currentVideo + 1 ) );
      // console.log( 'Playing next' );
    }
    prevVideo() {
      window.fwpCustomVideoPlayer.switch( 'prev' );
      // if( this.currentVideo <= 0 ) {return;}
      // this.switchVideo( ( this.currentVideo - 1 ) );
      // console.log( 'Playing Previous' );
    }
    icons( tr = 'video' ) {
      var icons = {
        video: '<svg width="24px" height="24px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"><circle cx="512" cy="512" r="512" style="fill:#607d8b"/><path d="M756 387.8c-7.9-4.6-17.7-4.6-25.6 0L640 432.4c-1.5-41.3-35.5-74-76.8-74H332.8c-42.4 0-76.8 34.4-76.8 76.8v153.6c0 42.4 34.4 76.8 76.8 76.8h230.4c41.3 0 75.3-32.7 76.8-74l91.1 45.6c3.5 1.8 7.3 2.8 11.3 2.8 4.8 0 9.5-1.3 13.6-3.8 7.5-4.7 12-12.9 12-21.8V409.6c0-8.8-4.5-17.1-12-21.8zm-167.2 201c0 14.1-11.5 25.6-25.6 25.6H332.8c-14.1 0-25.6-11.5-25.6-25.6V435.2c0-14.1 11.5-25.6 25.6-25.6h230.4c14.1 0 25.6 11.5 25.6 25.6v153.6zm128-15.9L640 534.5v-45.1l76.8-38.4v121.9z" style="fill:#fff"/></svg>'
      };
      return icons[ tr ];
    }

    tabs( args ) {
      const thisClass = this;
      var node, span, ul, li, a, i;
      ul = document.querySelector( '.fwp-body .tutor-course-single-content-wrapper .tutor-course-spotlight-wrapper > ul.tutor-course-spotlight-nav' );
      if( ul === null ) {return;}

      if( thisClass.lists.length >= 1 ) {thisClass.tabPlaylist( ul );}
      if( thisClass.attachments.length >= 1 ) {thisClass.tabAttachments( ul );}
      
    }
    tabPlaylist( ul ) {
      const thisClass = this;
      var tabs, tab, container, row, col, title, list, node, div, span, li, a, i, attach, card, cbody, crow, ccol0, ccol1, ccol2, ctitle, csize, clink, cicon;
      node = document.querySelector( '.fwp-body .tutor-course-single-content-wrapper .tutor-course-spotlight-wrapper ul.tutor-course-spotlight-nav .tutor-nav-link.is-active' );
      tabs = document.querySelector( '.fwp-body .tutor-course-single-content-wrapper .tutor-course-spotlight-wrapper .tutor-course-spotlight-tab .is-active' );

      if( node !== null ) {node.classList.remove( 'is-active' );}
      if( tabs !== null ) {tabs.classList.remove( 'is-active' );}

      li = document.createElement( 'li' );li.classList.add( 'tutor-nav-item' );
      a = document.createElement( 'a' );a.classList.add( 'tutor-nav-link', 'is-active' );
      a.setAttribute( 'data-tutor-nav-target', 'tutor-course-spotlight-playlist' );a.setAttribute( 'data-tutor-query-variable', 'page_tab' );a.setAttribute( 'data-tutor-query-value', 'playlist' );
      i = document.createElement( 'span' );i.classList.add( 'tutor-icon-media-manager', 'tutor-mr-8' );i.setAttribute( 'area-hidden', true );
      span = document.createElement( 'span' );span.innerText = thisClass.i18n.playlist;
      a.appendChild( i );a.appendChild( span );li.appendChild( a );
      ul.insertBefore( li, ul.querySelectorAll( 'li' )[1] ); // ul.appendChild( li );
      // Tab content
      tabs = document.querySelector( '.fwp-body .tutor-course-single-content-wrapper .tutor-course-spotlight-wrapper .tutor-course-spotlight-tab' );
      if( tabs === null ) {return;}
      tab = document.createElement( 'div' );tab.classList.add( 'tutor-tab-item', 'is-active' );tab.id = 'tutor-course-spotlight-playlist';
      container = document.createElement( 'div' );container.classList.add( 'tutor-container' );
      row = document.createElement( 'div' );row.classList.add( 'tutor-row', 'tutor-justify-center' );
      col = document.createElement( 'div' );col.classList.add( 'tutor-col-xl-8' );
      title = document.createElement( 'div' );title.classList.add( 'tutor-fs-5', 'tutor-fw-medium', 'tutor-color-black' );title.innerText = thisClass.i18n.lesson_playlist;

      attach = document.createElement( 'div' );attach.classList.add( 'tutor-course-attachments', 'tutor-row' );
      thisClass.lists.forEach( function( e, i ) {
        
        node = document.createElement( 'div' );node.classList.add( 'tutor-col-md-12', 'tutor-mt-16' );
        card = document.createElement( 'div' );card.classList.add( 'tutor-course-attachment', 'tutor-card', 'tutor-card-sm' );
        cbody = document.createElement( 'div' );cbody.classList.add( 'tutor-card-body' );
        crow = document.createElement( 'div' );crow.classList.add( 'tutor-row' );

        if( thisClass.config.playlist.icon ) {
          ccol0 = document.createElement( 'div' );ccol0.classList.add( 'tutor-col-auto', 'tutor-d-inline-flex', 'tutor-align-center' );
          cicon = document.createElement( 'span' );cicon.classList.add( 'tutor-icon-play-line' );cicon.setAttribute( 'area-hidden', true );
          ccol0.appendChild( cicon );crow.appendChild( ccol0 );
        }

        if( thisClass.config.playlist.title ) {
          ccol1 = document.createElement( 'div' );ccol1.classList.add( 'tutor-col', 'tutor-overflow-hidden', 'tutor-cursor-pointer' );
          ctitle = document.createElement( 'div' );ctitle.classList.add( 'tutor-fs-6', 'tutor-fw-medium', 'tutor-color-black', 'tutor-text-ellipsis', 'tutor-mb-4' );ctitle.innerText = e.title;
          ccol1.appendChild( ctitle );thisClass.eventListener( ctitle, i );
          if( e.sources[0] && e.sources[0].videoSize && e.sources[0].videoSize >= 0 ) {
            csize = document.createElement( 'div' );csize.classList.add( 'tutor-fs-7', 'tutor-color-muted' );csize.innerText = thisClass.i18n.size + ':' + e.sources[0].videoSize;
            ccol1.appendChild( csize );
          }
          crow.appendChild( ccol1 );
        }
        
        if( thisClass.config.playlist.download && e.sources[0] && e.sources[0].src ) {
          ccol2 = document.createElement( 'div' );ccol2.classList.add( 'tutor-col-auto' );
          clink = document.createElement( 'a' );clink.classList.add( 'tutor-iconic-btn', 'tutor-iconic-btn-secondary', 'tutor-stre-tched-link' );clink.setAttribute( 'download', e.title );clink.href = e.sources[0].src;clink.target = '_blank';
          cicon = document.createElement( 'span' );cicon.classList.add( 'tutor-icon-download' );cicon.setAttribute( 'area-hidden', true );
          clink.appendChild( cicon );ccol2.appendChild( clink );crow.appendChild( ccol2 );
        }

        cbody.appendChild( crow );card.appendChild( cbody );node.appendChild( card );
        attach.appendChild( node );
      } );
      // li = document.createElement( 'li' );li.classList.add( 'tutor-nav-item' );


      col.appendChild( title );col.appendChild( attach );row.appendChild( col );container.appendChild( row );tab.appendChild( container );
      tabs.appendChild( tab );
    }
    tabAttachments( ul ) {
      const thisClass = this;
      var tabs, tab, container, row, col, title, list, node, div, span, li, a, i, attach, card, cbody, crow, ccol1, ccol2, ctitle, csize, clink, cicon;
      tabs = document.querySelector( '.fwp-body .tutor-course-single-content-wrapper .tutor-course-spotlight-wrapper .tutor-course-spotlight-tab' );
      if( tabs === null || thisClass.config.attachments.column <= 0 ) {return;}

      
      li = document.createElement( 'li' );li.classList.add( 'tutor-nav-item' );
      a = document.createElement( 'a' );a.classList.add( 'tutor-nav-link' );
      a.setAttribute( 'data-tutor-nav-target', 'tutor-course-spotlight-attachments' );a.setAttribute( 'data-tutor-query-variable', 'page_tab' );a.setAttribute( 'data-tutor-query-value', 'attachments' );
      i = document.createElement( 'span' );i.classList.add( 'tutor-icon-file-pdf', 'tutor-mr-8' );i.setAttribute( 'area-hidden', true );
      span = document.createElement( 'span' );span.innerText = thisClass.i18n.attachments;
      a.appendChild( i );a.appendChild( span );li.appendChild( a );
      ul.appendChild( li );
      // Tab contents
      tab = document.createElement( 'div' );tab.classList.add( 'tutor-tab-item' );tab.id = 'tutor-course-spotlight-attachments';
      container = document.createElement( 'div' );container.classList.add( 'tutor-container' );
      row = document.createElement( 'div' );row.classList.add( 'tutor-row', 'tutor-justify-center' );
      col = document.createElement( 'div' );col.classList.add( 'tutor-col-xl-' + ( ( thisClass.config.attachments.column <= 2 ) ? '8' : '10' ) );
      title = document.createElement( 'div' );title.classList.add( 'tutor-fs-5', 'tutor-fw-medium', 'tutor-color-black' );title.innerText = thisClass.i18n.lesson_attachments;


      attach = document.createElement( 'div' );attach.classList.add( 'tutor-course-attachments', 'tutor-row' );
      thisClass.attachments.forEach( function( e, i ) {
        if( thisClass.config.attachments.column >= 1 ) {
          node = document.createElement( 'div' );node.classList.add( 'tutor-col-md-' + ( 12 / thisClass.config.attachments.column ), 'tutor-mt-16' );
          card = document.createElement( 'div' );card.classList.add( 'tutor-course-attachment', 'tutor-card', 'tutor-card-sm' );
          cbody = document.createElement( 'div' );cbody.classList.add( 'tutor-card-body' );
          crow = document.createElement( 'div' );crow.classList.add( 'tutor-row' );
          ccol1 = document.createElement( 'div' );ccol1.classList.add( 'tutor-col', 'tutor-overflow-hidden' );
          ctitle = document.createElement( 'div' );ctitle.classList.add( 'tutor-fs-6', 'tutor-fw-medium', 'tutor-color-black', 'tutor-text-ellipsis', 'tutor-mb-4' );ctitle.innerText = e.title;
          csize = document.createElement( 'div' );csize.classList.add( 'tutor-fs-7', 'tutor-color-muted' );csize.innerText = thisClass.i18n.size + ': ' + e.size;

          ccol1.appendChild( ctitle );ccol1.appendChild( csize );crow.appendChild( ccol1 );

          ccol2 = document.createElement( 'div' );ccol2.classList.add( 'tutor-col-auto' );
          clink = document.createElement( 'a' );clink.classList.add( 'tutor-iconic-btn', 'tutor-iconic-btn-secondary', 'tutor-stretched-link' );clink.href = e.url;clink.target = '_blank';
          if( thisClass.config.attachments.download ) {clink.setAttribute( 'download', e.title );}
          cicon = document.createElement( 'span' );cicon.classList.add( 'tutor-icon-' + ( ( thisClass.config.attachments.download ) ? 'download' : 'external-link' ) );cicon.setAttribute( 'area-hidden', true );

          clink.appendChild( cicon );ccol2.appendChild( clink );

          crow.appendChild( ccol1 );crow.appendChild( ccol2 );
          cbody.appendChild( crow );card.appendChild( cbody );node.appendChild( card );
          attach.appendChild( node );
        }
      } );

      col.appendChild( title );col.appendChild( attach );row.appendChild( col );container.appendChild( row );tab.appendChild( container );
      tabs.appendChild( tab );
    }
    eventListener( elem, i ) {
      elem.addEventListener( 'click', function( event ) {
        event.preventDefault();
        window.fwpCustomVideoPlayer.switch( i );
      } );
    }




    player_() {
      const thisClass = this;
      /**
       * YOUTUBE API https://stackoverflow.com/questions/43839217/how-do-i-retrieve-my-youtube-channel-playlists
       * BY CHANNEL https://content.googleapis.com/youtube/v3/channels?id=UC_x5XG1OV2P6uZZ5FSM9Ttw&part=snippet%2CcontentDetails%2Cstatistics&key=12345
       * FOR USERNAME https://content.googleapis.com/youtube/v3/channels?forUsername=ginocote&part=snippet%2CcontentDetails%2Cstatistics&key=12345
       * THEN GET BY UPLOAD ID AND LOAD API playlistItems BY CHANNEL ID GET ALL https://content.googleapis.com/youtube/v3/playlists?channelId=UC-VKI9vpl2tSyv0FK9E1-KA&maxResults=50&part=snippet&key=12345
       */
      var myPlaylist = [
        {
          type: "youtube",
          title: "Charlie Puth - Attention [Official Video]",
          author: "Charlie Puth",
          sources: [{ 
            src: "nfs8NYg7yQM", 
            type: "youtube"
          }],
          src: "nfs8NYg7yQM",
          poster: "https://img.youtube.com/vi/nfs8NYg7yQM/hqdefault.jpg"
        },
        {
          type: "youtube",
          title: "Avicii - SOS ft. Aloe Blacc (Unofficial Video)",
          author: "Avicii",
          sources: [{ 
            src: "RnVbU3NYrZw", 
            type: "youtube"
          }],
          poster: "https://i.ytimg.com/vi/RnVbU3NYrZw/default.jpg"
        },
        {
          type: "youtube",
          title: "Loud Luxury feat. brando - Body (Official Video HD)",
          author: "Loud Luxury",
          sources: [{ 
            src: "https://www.youtube.com/watch?v=UyxKu20xmBs", 
            type: "youtube"
          }],
          poster: "https://i.ytimg.com/vi/UyxKu20xmBs/default.jpg"
        },
        {
          type: "youtube",
          title: "Loud Luxury x anders - Love No More (Official Music Video)",
          author: "Loud Luxury",
          sources: [{ 
            src: "https://www.youtube.com/watch?v=PJF0SBwfDq8", 
            type: "youtube"
          }],
          poster: "https://img.youtube.com/vi/PJF0SBwfDq8/hqdefault.jpg"
        },
        {
          type: "audio",
          title: "Clublife by Tiësto 542 podcast ",
          author: "Tiësto",
          sources: [{ 
            src: "http://feed.pippa.io/public/streams/593eded1acfa040562f3480b/episodes/59c0c870ed6a93163c0a193d.m4a", 
            type: "m4v"
          }],
          poster: "https://img.youtube.com/vi/r6KXy0j85AM/hqdefault.jpg"
        },
        {
          type: "audio",
          title: "Vocal Trance Vol 261",
          author: "Sonnydeejay",
          sources: [{ 
            src: "http://archive.org/download/SonnydeejayVocalTranceVol261/Sonnydeejay%20-Vocal%20Trance%20vol%20261.mp3", 
            type: "mp3"
          }],
          poster: "http://4.bp.blogspot.com/-d6IPBUIj6YE/ThpRaIGJXtI/AAAAAAAABQ8/54RNlCrKCv4/s1600/podcast.jpg"
        },
        {
          type: "youtube",
          title: "2 hours Trance Music - Armin Van Buuren",
          author: "Armin van Buuren",
          sources: [{ 
            src: "https://www.youtube.com/watch?v=r6KXy0j85AM", 
            type: "youtube"
          }],
          poster: "https://img.youtube.com/vi/r6KXy0j85AM/hqdefault.jpg"
        },
        {
          type: "youtube",
          title: "2 hours Trance Music - Armin Van Buuren",
          author: "Armin van Buuren",
          sources: [{ 
            src: "https://www.youtube.com/watch?v=r6KXy0j85AM", 
            type: "youtube"
          }],
          poster: "https://img.youtube.com/vi/r6KXy0j85AM/hqdefault.jpg"
        },
        {
          type: "youtube",
          title: "2 hours Trance Music - Armin Van Buuren",
          author: "Armin van Buuren",
          sources: [{ 
            src: "https://www.youtube.com/watch?v=r6KXy0j85AM", 
            type: "youtube"
          }],
          poster: "https://img.youtube.com/vi/r6KXy0j85AM/hqdefault.jpg"
        },
        {
          type: "youtube",
          title: "2 hours Trance Music - Armin Van Buuren",
          author: "Armin van Buuren",
          sources: [{ 
            src: "https://www.youtube.com/watch?v=r6KXy0j85AM", 
            type: "youtube"
          }],
          poster: "https://img.youtube.com/vi/r6KXy0j85AM/hqdefault.jpg"
        },
        {
          type: "youtube",
          title: "2 hours Trance Music - Armin Van Buuren",
          author: "Armin van Buuren",
          sources: [{ 
            src: "https://www.youtube.com/watch?v=r6KXy0j85AM", 
            type: "youtube"
          }],
          poster: "https://img.youtube.com/vi/r6KXy0j85AM/hqdefault.jpg"
        },
        {
          type: "youtube",
          title: "2 hours Trance Music - Armin Van Buuren",
          author: "Armin van Buuren",
          sources: [{ 
            src: "https://www.youtube.com/watch?v=r6KXy0j85AM", 
            type: "youtube"
          }],
          poster: "https://img.youtube.com/vi/r6KXy0j85AM/hqdefault.jpg"
        }
      ];
      var apikey = "<YOUR_YOUTUBE_API_KEY>"; // GET YOUR YOUTUBE API KEY
      //var apikey = ""; // ONLY FOR MY CUSTOM PLAYLIST NO NEED FOR YOUTUBE API KEY
      var target = ".js-player";
      var limit = 30;

      $(document).ready(function() {
        // thisClass.loadPlaylist(target, apikey, limit = 20, myPlaylist); // LOAD JSON PLAYLIST
        thisClass.loadPlaylist(target, apikey, limit, myPlaylist);  // LOAD YOUTUBE OR USER VIDEO LIST
        
      }); // JQUERY READY END
    }
    loadPlaylist(target, apikey, limit = 20, myPlaylist) {
      const thisClass = this;
      $("li.pls-playing").removeClass("pls-playing");
      $(".plyr-playlist-wrapper").remove();

      limit = limit-1;
      if (myPlaylist) {
        thisClass.PlyrPlaylist( ".plyr-playlist", myPlaylist, limit );
        //return 
      } else{
        var ytplaylist = $(target).attr("data-ytpls");
        var ytuser = $(target).attr("data-user");
        if( ytplaylist) {
          thisClass.getTYPlaylist( ytplaylist, apikey, limit)
        } else if (ytuser) {
          alert(ytuser);
        }
        
      }
    }
    getTYPlaylist(playlistId, apikey, limit) {
      const thisClass = this;
      $.ajax({
        url:
          "https://content.googleapis.com/youtube/v3/playlistItems?&key=" +
          apikey +
          "&maxResults=" +
          limit +
          "&part=id,snippet&playlistId=" +
          playlistId +
          "&type=video",
        dataType: "jsonp",
        success: function(data) {
          console.log(data.items);
          //console.log(data.items[0].snippet.title);

          resultData = thisClass.youtubeParser(data);

          console.log(resultData);

          thisClass.PlyrPlaylist(".plyr-playlist", resultData, limit);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert(textStatus, +" | " + errorThrown);
        }
      });
    }
    PlyrPlaylist(target, myPlaylist, limit) {
      const thisClass = this;
      $('<div class="plyr-playlist-wrapper"><ul class="plyr-playlist"></ul></div>').insertAfter("#player");

      var startwith = 0; // Maybe a playlist option to start with choosen video

      var playingclass = "";
      var items = [];
      //var playing == 1 ;
      $.each(myPlaylist, function(id, val) {
        //items.push('<li>' + option.title + '</li>');
        //alert(id)

        //console.log(val);

        if (0 === id) playingclass = "pls-playing";
        else playingclass = "";

        items.push(
          '<li class="' +playingclass +'"><a href="#" data-type="' +val.sources[0].type +'" data-video-id="' +val.sources[0].src +'"><img class="plyr-miniposter" src="' + val.poster + '"> ' + val.title +" - " +val.author +"</a></li> ");
        
        if (id == limit) 
          return false;
        
      });
      $(target).html(items.join(""));
    }
    playListEvent( players ) {
      players[0].on("ready", function(event) {
        //$(".plyr-playlist .pls-playing").find("a").one().trigger("click");
        console.log("Ready.....................................................");
        players[0].play();
        
        
        if(addbuttons){
          
          $(".plyr-playlist .pls-playing").find("a").trigger("click");
          
          $('<button type="button" class="plyr-prev"><i class="fa fa-step-backward fa-lg"></i></button>').insertBefore('.plyr__controls [data-plyr="play"]');

          $('<button type="button" class="plyr-next"><i class="fa fa-step-forward fa-lg"></i></button>').insertAfter('.plyr__controls [data-plyr="pause"]');
          
          addbuttons = false ;
        }
        
      }).on("ended", function(event) {
        var $next = $(".plyr-playlist .pls-playing")
          .next()
          .find("a")
          .trigger("click");
        //$next.parent().addClass("pls-playing");
      });
      $(document).on("click", "ul.plyr-playlist li a", function(event) {
        //$("ul.plyr-playlist li a").on("click", function(event) {
        event.preventDefault();

        $("li.pls-playing").removeClass("pls-playing");
        $(this)
          .parent()
          .addClass("pls-playing");

        var video_id = $(this).data("video-id");
        var video_type = $(this).data("type");
        var video_title = $(this).text();

        //alert(video_id);

        players[0].source({
          type: "video",
          title: "video_title",
          sources: [{ src: video_id, type: video_type }]
        });

        //ScrollTo($(".pls-playing").attr("href"), 500,0,10);

        $(".plyr-playlist").scrollTo(".pls-playing", 300);

        // players[0].on("ended", function(event) {
        //   console.log("test");
        // });
      })
      .on("click", ".plyr-prev", function(event) {
        var $next = $(".plyr-playlist .pls-playing")
          .prev()
          .find("a")
          .trigger("click");
      })
      .on("click", ".plyr-next", function(event) {
        var $next = $(".plyr-playlist .pls-playing")
          .next()
          .find("a")
          .trigger("click");
      });
      // http://jsfiddle.net/onigetoc/cb2u1y4k/
      
    }
    youtubeParser(data) {
          
      const thisClass = this;
      var new_Data = data.items.map(function(item) {
        var thumb;

        if (item.snippet.thumbnails) {
          if (item.snippet.thumbnails.default)
            //live?
            thumb = item.snippet.thumbnails.default.url;

          if (item.snippet.thumbnails.medium)
            //live?
            thumb = item.snippet.thumbnails.default.url;
        }

        return {
          //type: "youtube",
          title: item.snippet.title,
          description: item.snippet.description,
          author: item.snippet.channelTitle,
          sources: [{ 
            src: item.snippet.resourceId.videoId, 
            type: item.kind.split('#')[0] 
          }],
          poster: thumb
        };
      });

      console.log(new_Data);

      // var output = {
      //   item: new_Data
      // };

      return new_Data;
    }
    scroll() {
      /****** GC ScrollTo **********/
      // mine: https://jsfiddle.net/onigetoc/5kh0e5f4/
      // https://stackoverflow.com/questions/2346011/how-do-i-scroll-to-an-element-within-an-overflowed-div
      $.fn.scrollTo = function(elem, speed, margin) {
        $(this).animate(
          {
            scrollTop:
              $(this).scrollTop() -
              $(this).offset().top +
              $(elem).offset().top
          },
          speed == undefined ? 1000 : speed
        );
        return this;
      };
    }
	}
  // Execute functions
  new buyerRequestDetails();
} )( jQuery );
