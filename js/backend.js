( function ( $ ) {
	/**
	 * Class Frontend Main JS.
	 */
	class FUTUREWORDPRESS_PROJECT_SEOJAWS_BACKEND {
		/**
		 * Contructor.
		 */
		constructor() {
      this.ajaxUrl = fwpSeoJawssiteConfig?.ajaxUrl ?? '';
      this.postUrl = fwpSeoJawssiteConfig?.postUrl ?? '';
			this.ajaxNonce = fwpSeoJawssiteConfig?.ajax_nonce ?? '';
			this.confirmAutoGenerate = fwpSeoJawssiteConfig?.confirmAutoGenerate ?? 'Are you sure yo want to generate lessons autometically? Press okay to continue, cancel to stop process.';
			this.confirmAutoRemoveLessons = fwpSeoJawssiteConfig?.confirmAutoRemove ?? 'Are you sure yo want to remove all lessons from currently visible topic.';
			this.selectALesson = fwpSeoJawssiteConfig?.selectALesson ?? 'Select a Lesson';

			this.init();
		}
		init() {
      const urlSearchParams = new URLSearchParams(window.location.search);
      const params = Object.fromEntries(urlSearchParams.entries());
      this.course_id = ( params.post ) ? params.post : 0;
      this.hasLessons = false;

      
			this.autoGenerateLessons();
      this.onSelectChange();
      this.selectMetaBox();
    }
    encodeShortCode( str ) {
      str = str.replace( "'", '-apostrophe-' );
      str = str.replace( '"', '-dquatation-' );
      str = str.replace( '[', '-3rdbracketsrt-' );
      str = str.replace( ']', '-3rdbracketend-' );
      return str;
    }
		generateTurorAutoLesson( elem ) {
			const thisClass = this;const topic = document.querySelector( 'body.tutor-screen-course-builder #tutor-course-content-builder-root #tutor-course-content-wrap .tutor-topics-wrap .tutor-topics-body:not([style="display: none;"]) .create-lesson-in-topic-btn' );
			if( topic ) {
				var incritment = 0, nodes = document.querySelectorAll( 'select#fwp_tutor_shortcode_select-' + ( thisClass.hasLessons ? 'lesson' : 'topic' ) + ' option' ), _nonce = _tutorobject?._tutor_nonce??'';
				elem.classList.add( 'is-loading' );
				// console.log( params );
				nodes.forEach( function( e, i ) {
					var title = e.value, path = e.parentNode.dataset.path, srcPath = path + "/" + title, args, topic_id = topic.dataset.topicId, lesson_id = topic.dataset.lessonId, course_id = thisClass.course_id, shortcode = '', content = '<p><br data-mce-bogus="1"></p>';// .replace( "'", '-apostrophe-' )
          shortcode = "[fwpcourselesson src='" + thisClass.encodeShortCode( srcPath ) + "' subtitle='srt' course='" + course_id + "']";
					// console.log( shortcode );
					if( title != '' && title != 'link' ) {
						var args = title.split( ' ' );incritment++;
						if( args.length >= 1 ) {
							title = title.substr( ( title[0].length + 1 ) );
						}
						fetch( thisClass.ajaxUrl, {
							"headers": {
							"accept": "*/*",
							"accept-language": "en-US,en;q=0.9,bn;q=0.8,ar;q=0.7,ur;q=0.6",
							"content-type": "application/x-www-form-urlencoded; charset=UTF-8",
							"sec-ch-ua": "\"Not?A_Brand\";v=\"8\", \"Chromium\";v=\"108\", \"Google Chrome\";v=\"108\"",
							"sec-ch-ua-mobile": "?0",
							"sec-ch-ua-platform": "\"Windows\"",
							"sec-fetch-dest": "empty",
							"sec-fetch-mode": "cors",
							"sec-fetch-site": "same-origin",
							"x-requested-with": "XMLHttpRequest"
							},
							"referrer": thisClass.postUrl + "?post=1718&action=edit",
							"referrerPolicy": "strict-origin-when-cross-origin",
							"body": "_tutor_nonce=" + _nonce + "&_wp_http_referer=" + encodeURIComponent( '/wp-admin/admin-ajax.php' ) + "&action=tutor_modal_create_or_update_lesson&lesson_id=" + lesson_id + "&current_topic_id=" + topic_id + "&lesson_title=" + encodeURIComponent( title ) + "&tutor_lesson_modal_editor=&_lesson_thumbnail_id=&video%5Bsource%5D=shortcode&video%5Bsource_video_id%5D=&video%5Bposter%5D=&video%5Bsource_external_url%5D=&video%5Bsource_shortcode%5D=" + encodeURIComponent( shortcode ) + "&video%5Bsource_youtube%5D=&video%5Bsource_vimeo%5D=&video%5Bsource_embedded%5D=&video%5Bruntime%5D%5Bhours%5D=00&video%5Bruntime%5D%5Bminutes%5D=00&video%5Bruntime%5D%5Bseconds%5D=00&lesson_content=" + encodeURIComponent( content ) + "&video%5Bsource_path%5D=" + encodeURIComponent( srcPath ) + "&is_html_active=false",
							"method": "POST",
							"mode": "cors",
							"credentials": "include"
						} ).then((response) => response.json()).then((data) => {
							incritment--;
							if( incritment <= 0 ) {
								document.getElementById( 'publish' ).click();
								elem.classList.remove( 'is-loading' );
							}
						} );
					}
				} );
			}
		}
		removeGeneratedLessons() {
			const thisClass = this;
			var incritment = 0, _nonce = _tutorobject?._tutor_nonce??'', nodes = document.querySelectorAll( '.tutor-delete-lesson-btn.tutor-iconic-btn:not(.is-loading)' ), lesson;
			thisClass.totalDeleLessonRqst = nodes.length;
			nodes.forEach( function( e, i ) {
				if( thisClass.totalDeleLessonRqst >= 1 && i <= 50 ) {
					thisClass.totalDeleLessonRqst = ( thisClass.totalDeleLessonRqst - 1 );
					// e.click();
					e.classList.add( 'is-loading' );
					var lesson_id = e.dataset.lessonId;incritment++;
					fetch( thisClass.ajaxUrl, {
						"headers": {
						"accept": "*/*",
						"accept-language": "en-US,en;q=0.9,bn;q=0.8,ar;q=0.7,ur;q=0.6",
						"content-type": "application/x-www-form-urlencoded; charset=UTF-8",
						"sec-ch-ua": "\"Not?A_Brand\";v=\"8\", \"Chromium\";v=\"108\", \"Google Chrome\";v=\"108\"",
						"sec-ch-ua-mobile": "?0",
						"sec-ch-ua-platform": "\"Windows\"",
						"sec-fetch-dest": "empty",
						"sec-fetch-mode": "cors",
						"sec-fetch-site": "same-origin",
						"x-requested-with": "XMLHttpRequest"
						},
						"referrer": thisClass.postUrl + "?post=1718&action=edit",
						"referrerPolicy": "strict-origin-when-cross-origin",
						"body": "_tutor_nonce=" + _nonce + "&lesson_id=" + lesson_id + "&action=tutor_delete_lesson_by_id",
						"method": "POST",
						"mode": "cors",
						"credentials": "include"
					}).then((response) => response.json()).then((data) => {
						incritment--;
						lesson = document.getElementById( 'tutor-lesson-' + lesson_id );
						if( lesson ) {
							lesson.remove();
						}
						if( incritment <= 0 ) {
							if( thisClass.totalDeleLessonRqst >= 1 ) {
								thisClass.removeGeneratedLessons();
							} else {
								document.getElementById( 'publish' ).click();
							}
						}
					} );
				}
			} );
		}
		autoGenerateLessons() {
			const thisClass = this;var incritment = 0, _nonce = _tutorobject?._tutor_nonce??'';
			document.querySelectorAll( '.tutor-fwp-generate-autometically-btn[data-topic-id][data-course-id]' ).forEach( function( e, i ) {
				e.addEventListener( 'click', function( event, ei ) {
					event.preventDefault();
					var topic_id = this.dataset.topicId, course_id = this.dataset.courseId;
					if( confirm( thisClass.confirmAutoGenerate ) ) {
						thisClass.generateTurorAutoLesson( this );
					}
				} );
			} );

      document.addEventListener( 'keypress', function( pressed ) {
        // https://www.toptal.com/developers/keycode/for/delete
        pressed = window.event ? event : pressed;
        console.log( pressed );//  && pressed.altKey
        if( pressed.ctrlKey && pressed.shiftKey && pressed.which == 11 ) {
          if( confirm( thisClass.confirmAutoRemoveLessons ) ) {
            thisClass.removeGeneratedLessons();
          }
        }
      } );
		}
    onSelectChange() {
      const thisClass = this;var node, loading = 0, _nonce = '', topic_path, topic, lesson;
      topic = node = document.getElementById( 'fwp_tutor_shortcode_select-topic' );
      if( node ) {
        node.addEventListener( 'change', function( e ) {
          var path = this.dataset.path;
          node = document.getElementById( 'fwp_tutor_shortcode_select-lesson' );
          if( node ) {
            topic_path = path + "/" + this.value;
            node.dataset.path = topic_path;
            node.classList.add( 'is-loading' );loading++;
            var defltOpt = document.createElement( 'option' );defltOpt.innerHTML = thisClass.selectALesson;defltOpt.value = 'link';defltOpt.dataset.icon = 'link';
            node.innerHTML = '';node.appendChild( ( node.options[0] ) ? node.options[0] : defltOpt );
            thisClass.hasLessons = false;
            fetch( thisClass.ajaxUrl, {
              "headers": {
                "accept": "*/*",
                "accept-language": "en-US,en;q=0.9,bn;q=0.8,ar;q=0.7,ur;q=0.6",
                "content-type": "application/x-www-form-urlencoded; charset=UTF-8",
                "sec-ch-ua": "\"Not?A_Brand\";v=\"8\", \"Chromium\";v=\"108\", \"Google Chrome\";v=\"108\"",
                "sec-ch-ua-mobile": "?0",
                "sec-ch-ua-platform": "\"Windows\"",
                "sec-fetch-dest": "empty",
                "sec-fetch-mode": "cors",
                "sec-fetch-site": "same-origin",
                "x-requested-with": "XMLHttpRequest"
              },
              "referrer": thisClass.postUrl + "?post=1718&action=edit",
              "referrerPolicy": "strict-origin-when-cross-origin",
              "body": "_fwp_nonce=" + _nonce + "&topic_path=" + topic_path + "&action=fwp_project_seojaws_get_lessons",
              "method": "POST",
              "mode": "cors",
              "credentials": "include"
            }).then((response) => response.json()).then((data) => {
              loading--;if( loading <= 0 ) {node.classList.remove( 'is-loading' );}
              if( data.success ) {
                var lists = data.data, options;
                lists.forEach( function( title, li ) {
                  options = document.createElement( 'option' );options.value = title;
                  options.innerHTML = title;node.appendChild( options );
                } );
                if( lists.length >= 1 ) {thisClass.hasLessons = true;}
                // console.log( node );
              } else {
                console.log( data.data );
              }
            } );
          }
        } );
      }
      lesson = node = document.getElementById( 'fwp_tutor_shortcode_select-lesson' );
      if( node ) {
        [ topic, lesson ].forEach( function( el, ei ) {
          el.addEventListener( 'change', function( e ) {
            var path = this.dataset.path;
            document.getElementById( 'fwp_tutor_shortcode_textarea' ).value = "[fwpcourselesson src='" + thisClass.encodeShortCode( path + "/" + this.value ) + "' subtitle='srt' course='" + this.dataset.course + "']";
          } );
        } );
      }
    }
    selectMetaBox() {
      const thisClass = this;var node, loading = 0, _nonce = '', topic_path;
      // var fwp_toggle_download_options = function( val ) ;
			// var fwp_custom_metabox_for_select_cource = ;
      node = document.getElementById( 'fwp-certificate-link' );
      if( node ) {
        node.addEventListener( 'change', async function( e ) {
          var val = this.value, id = document.getElementById( 'fwp-certificate-url' );id.type = 'text';id.value = location.origin + "/mycourses/download/" + thisClass.course_id + "/" + val + "/";
          var selectedText = ( e.options && e.options[e.selectedIndex] ) ? e.options[e.selectedIndex].text : '';
          var select = document.getElementById( 'fwp_tutor_shortcode_select-topic' ), option, node = document.createElement( 'select' );
  
          // if( ( 'clipboard' in navigator ) && ( 'writeText' in navigator.clipboard ) ) {navigator.clipboard.writeText( selectedText );}
  
          jQuery.ajax( {
            url: thisClass.ajaxUrl,
            type: "POST", // method type
            data: { action: 'fwp_tutor_shortcode_select', url: val },
            timeout: 20000,
            dataType: "json",
            // async: true,
            success: function( json, textStatus, jqXHR ) {
              select.setAttribute( 'data-path', selectedText );
              if( json.success ) {
                node.appendChild( select.options[0] );
                json.data.forEach( function( e, i ) {
                  option = document.createElement( 'option' );option.value = e[1];option.setAttribute( 'data-icon', 'link' );option.innerText = e[1];node.appendChild( option );
                } );
                select.innerHTML = node.innerHTML;
                // console.log( node );
              } else {
                console.log( [ json, textStatus, jqXHR ] );
              }
            },
            error: function( jqXHR, textStatus, errorThrown ) {
                console.log( {
                  jqXHR: jqXHR,
                  TestStatus: textStatus,
                  ErrorThrown: errorThrown
                } );
            },
            // beforeSend: function (xhr, settings) {},
            // dataFilter: function (data, type) {},
            // complete: function (xhr, status) {},
          } );
        } );
      }
      node = document.querySelector( '.fwp-switcher-input[name="_fwp_course_status"]' );
      if( node ) {
        node.addEventListener( 'change', function( e ) {
          thisClass.switcherDownload( this.checked );
        } );
      }
    }
    switcherDownload( value ) {
      var id = document.getElementById( 'fwp-is-opened' );
      if( value === true ) {
        id.classList.add( 'opened' );
      } else {
        id.classList.remove( 'opened' );
      }
    }
	}

	new FUTUREWORDPRESS_PROJECT_SEOJAWS_BACKEND();
} )( jQuery );