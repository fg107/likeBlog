/*!
 *  jsMap v4.1.0
 *  Copyright (C) 2018-2019, ZhaoGang
 *  Released under the MIT license.
 */
!(function ( global, factory ) {

    if ( typeof define === "function" && define.amd ) { 
        define( [ "jsmap" ], factory );
    } else if ( typeof module !== "undefined" && typeof exports === "object" ) {
        module.exports = factory();
    } else {
        global.jsMap = factory();
    }

})( typeof window !== "undefined" ? window : this, function () {

	// 变量常量
	var UA = navigator.userAgent.toLowerCase(),
		IsMobile = !!( UA.match( /(ios|iphone|ipod|ipad|android)/ ) && "ontouchend" in document ),
		CacheJSON = {};
	var $tip;

	// 获取 DOM
	function $ ( elem, context ) {
		return [].slice.call( typeof elem === "object" ? ( elem.length ? elem : [ elem ] ) : ( context || document ).querySelectorAll( elem ) );
	}

	// 方法合集
	var Methods = {
		getCSS: function ( elem, name ) {
			return window.getComputedStyle( elem, null ).getPropertyValue( name );
		},
		setCSS: function ( elem, css ) {
			$( elem ).forEach(function ( el ) {
				for ( var name in css ) {
					el.style[ name ] = css[ name ];
				}
			})
		},
		setAttr: function ( elem, props ) {
			$( elem ).forEach(function ( el ) {
				for ( var name in props ) {
					el.setAttribute( name, props[ name ] );
				}
			})
		},
		type: function ( obj ) {
			return {}.toString.call( obj ).replace( /(\[object |\])/g, "" ).toLowerCase();
		},
		isEmptyObject: function ( obj ) {
			return Object.keys( obj ).length === 0;
		}
	};

	// 发送请求获取地图数据
	function Request ( url, done ) {
		var xmlHttp = new XMLHttpRequest();
		xmlHttp.onreadystatechange = function () {
			if ( xmlHttp.readyState === 4 ) {
				var status = xmlHttp.status;
				if ( ( ( status >= 200 && status < 300 ) || status === 304 ) ) {
					done( JSON.parse( xmlHttp.responseText.trim() ) );
				}
	        }
		}
		xmlHttp.open( "GET", url );
		xmlHttp.send( null );
	}

	// 在支持 classList 的 IE 浏览器中, classList 属性是在 HTMLElement.prototype 上定义的
	// 若要使其应用在 svg 元素上, 需要将 classList 定义在 Element.prototype 上
	if ( !Object.getOwnPropertyDescriptor( Element.prototype, "classList" ) ){
	    if ( HTMLElement && Object.getOwnPropertyDescriptor( HTMLElement.prototype, "classList" ) ) {
	        Object.defineProperty( Element.prototype, "classList", Object.getOwnPropertyDescriptor( HTMLElement.prototype, "classList" ) );
	    }
	}

	// 默认配置
	var defaults = {
		taiwanJSON: false,
		nanhaizhudaoJSON: false,
		name: "china",
		width: 900,
		stroke: {
			width: 1,
			color: "#f3f3f3"
		},
		fill: {
			basicColor: "#3f99f9",
			hoverColor: "#0880ff",
			clickColor: "#006bde"
		},
		areaName: {
			show: false,
			size: 12,
			basicColor: "#333",
			clickColor: "#fff"
		},
		disabled: {
			color: "#bfbfbf",
			except: false,
			name: []
		},
		zoom: {
			disabled: true,
			max: 5,
			zoomRange: true,
			wheelZoom: true
		},
		marker: {
			disabled: true,
			icon: "data:img/jpg;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAMAAACdt4HsAAAAjVBMVEVMaXEsLCwoKCgrKyssLCwsLCwsLCwgICAqKioqKiorKyspKSkrKysrKyssLCwrKysrKysrKyssLCwrKysqKiosLCwrKyssLCwqKiosLCz///9SUlKVlZW8vLw5OTnX19fx8fF6enpgYGBhYWFTU1NFRUXk5ORtbW3Kysqvr697e3tGRkaIiIg4ODiHh4fw9bOuAAAAGXRSTlMA7yDQv4DwEGAwoFDfsMBw4F9Az5Cvj39PrlPZPAAAAWxJREFUeNrt1tluwyAQBdCxTUzwEmfrMjhu9qXr/39eq0oVkAZfUF7ykPNqezR3QGC6u3Fy8ihEUgnx9Cwp3kSw5WES+3nFZ2ZTCpcKvkAEB1EJX1QpCjJkn6SkACX7JSq+f1clCZAz7iUIGDAwBA24ATaLrlu0boiIBtql/nVqw1uwG1jrPyu7wox6KDbalTYV9myk5Ddn46Qtr4EZRua1vXZ8mic1+bHxpR0b82QUVmChHWtrP5NX6i9wZIO8pmwctWPHRliEl5W2HDi6gJthzUZGfmO7haX5vmMOW4WaL1boXtgyDz/NPg76x/KNHSXBdTR22+07n6E+Y4Zq6tMwgM4DyciY+hUMDNCtxv1ySUCBGkBS1AA0AEsAyZy9MrrydlUUpEYTxCHAHoJKEAArQABIZvzPiGKoHGyh+D+tKUUqwACwDBxDUGqNIQMDQBddrggAgywJAINsCAB/LAUBYEdmdA3ZNHR3874BBc+FYRSdidoAAAAASUVORK5CYII=",
			click: function () {}
		},
		selected: [],
		hide: [],
		multiple: false,
		tip: true,
		defaultInteractive: true,
		hoverCallback: function () {},
		clickCallback: function () {}
	};

	// 整合参数
	function mergeParam ( param ) {
		for ( var name in defaults ) {
			var v = defaults[ name ];
			if ( Methods.type( v ) !== "object" ) {
				if ( param[ name ] === undefined ) {
					param[ name ] = v;
				}
			} else {
				for ( var _name in v ) {
					if ( param[ name ] ) {
						if ( param[ name ][ _name ] === undefined ) {
							param[ name ][ _name ] = v[ _name ];
						}
					} else {
						param[ name ] = v;
					}
				}
			}
		}
		return param;
	}

	// 核心程序
	var jsMap = {
		version: "4.1.0",
		config: function ( selector, jsonData, options ) {
			var opt = mergeParam( options || {} );

			// 必须传入地图数据
			if ( !jsonData || Methods.type( jsonData ) !== "object" ) {
				console.warn( "来自 jsMap 的提示：[ 请传入地图数据 ]" );
				return;
			}

			// 暂无台湾省和南海诸岛的详细地图数据
			if ( opt.name === "taiwan" && !opt.taiwanJSON ) {
				console.warn( "来自 jsMap 的提示：[ 很抱歉，暂无台湾省的详细地图数据。 ]" );
				return;
			}
			if ( opt.name === "nanhaizhudao" && !opt.nanhaizhudaoJSON ) {
				console.warn( "来自 jsMap 的提示：[ 很抱歉，暂无南海诸岛的详细地图数据。 ]" );
				return;
			}

			// 取出要绘制的区域的 json 数据
			var mapData = jsonData[ opt.name ];

			var path = "",
				text = "",
				areaBox = [];

			for ( var i in mapData ) {
				var v = mapData[ i ];

				// 存储地区名称
				areaBox.push( i );

				// 绘制 path 路径
				path += '\
					<path \
						d="' + v.svg + '" \
						class="jsmap-' + i + '" \
						data-name="' + v.name + '" \
						data-id="' + i + '" \
						style="cursor:pointer;">\
					</path>\
				';

				// 设置文字
				text += '\
					<text \
						x="' + v.textPosition[ 0 ] + '" \
						y="' + v.textPosition[ 1 ] + '" \
						class="jsmap-' + i + '" \
						data-name="' + v.name + '" \
						data-id="' + i + '" \
						style="cursor:pointer;">' + v.name + '</text>\
				';
			}

			path = '<div \
				class="jsmap-svg-container" \
				style="\
					position:absolute;\
					top:0;\
					left:0;\
					padding:0;\
					margin:0;\
					transform-origin:center;\
					-webkit-user-select:none;\
					-moz-user-select:none;\
					-ms-user-select:none;\
					user-select:none;\
					-webkit-tap-highlight-color:transparent;"\
				>\
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 575 470">' + path + text + '</svg>\
			</div>';

			$( selector ).forEach(function ( $el ) {

				// 记录当前指定的行内样式
				var cssCache = {
					width: $el.style.width,
					height: $el.style.height,
					padding: $el.style.padding,
					position: $el.style.position
				};
				$el[ "__jsmap_csscache__" ] = cssCache;

				// 给目标元素设置样式并添加数据存储
				if ( Methods.getCSS( $el, "position" ) === "static" ) {
					$el.style.position = "relative";
				}

				$el.classList.add( "jsmap-container" );

				// 对宽高的处理
				var height;
				if ( String( opt.width ).match( /^([1-9][0-9]{0,1}|100)%$/ ) ) {
					opt.width = "100%";
					height = parseFloat( Methods.getCSS( $el, "width" ) ) / 1.8 + "px";
				} else {
					opt.width = parseInt( opt.width ) + "px";
					height = parseInt( opt.width ) / 1.8 + "px";
				}

				Methods.setCSS($el, {
					width: opt.width,
					height: height,
					padding: 0
				});
				$el.innerHTML = path;
				$el[ "__jsmap_jsondata__" ] = jsonData;
				$el[ "__jsmap_options__" ] = opt;

				var $container = $( ".jsmap-svg-container", $el )[ 0 ],
					$svg = $( "svg", $container )[ 0 ],
					$path = $( "path", $svg ),
					$text = $( "text", $svg ),
					$pathText = $( "path, text", $svg );

				// 禁用默认交互 [ 悬浮效果和点击效果 ]
				if ( !opt.defaultInteractive ) {
					$container.defaultInteractive = true;
				}

				Methods.setCSS($container, {
					width: opt.width,
					height: height
				});

				Methods.setAttr($svg, {
					width: opt.width === "100%" ? opt.width : parseFloat( opt.width ),
					height: parseFloat( height )
				});
				Methods.setCSS($svg, {
					position: "relative",
					overflow: "hidden",
					marginLeft: opt.name === "china" ? ( window.innerWidth > 700 ? "-50px" : "-30px" ) : 0
				});

				$text.length && $( $text ).forEach(function ( text ) { 

					// 如果 text 不是下列匹配的区域
					// 则禁用任何鼠标行为
					if ( !text.getAttribute( "data-id" ).match( /(shanghai|xianggang|aomen|nanhaizhudao|tianjin|beijing)/ ) ) {
						text.style.pointerEvents = "none";
					}
				})

				// 隐藏指定的区域
				if ( Array.isArray( opt.hide ) && opt.hide.length ) {
					opt.hide.forEach(function ( v ) {

						// 支持汉字和全拼两种形式
						$( $pathText ).forEach(function ( pathText ) {
							if ( pathText.getAttribute( "data-id" ) === v || pathText.getAttribute( "data-name" ) === v ) {
								pathText.style.display = "none";
							}
						})
					})
				}

				var fillBasicColor = opt.fill.basicColor;

				// 填充色 - 统一设置
				if ( typeof fillBasicColor === "string" ) {
					Methods.setAttr($path, {
						fill: fillBasicColor,
						"data-fill": fillBasicColor
					});
				}

				// 填充色 - 单独设置
				if ( Methods.type( fillBasicColor ) === "object" && !Methods.isEmptyObject( fillBasicColor ) ) {
					Methods.setAttr($path, {
						fill: defaults.fill.basicColor,
						"data-fill": defaults.fill.basicColor
					});
					for ( var name in fillBasicColor ) {
						var v = fillBasicColor[ name ];
						Methods.setAttr($( ".jsmap-" + name, $svg ), {
							fill: v,
							"data-fill": v
						});
					}
				}

				// 给 path 添加过渡时间
				var timer = window.setTimeout(function () {
					$( $path ).forEach(function ( path ) {
						path.style.transition = ".15s";
					})
					window.clearTimeout( timer );
				}, 0);

				// 描边
				Methods.setAttr($path, {
					stroke: opt.stroke.color,
					"stroke-width": opt.stroke.width
				});

				// 文字颜色大小
				Methods.setAttr($text, {
					fill: opt.areaName.basicColor,
					"font-size": opt.areaName.size
				});

				// 禁用指定的区域
				var disabledName = opt.disabled.name;
				if ( Array.isArray( disabledName ) && disabledName.length ) {
					function setDisabled ( elem ) {
						$( elem ).forEach(function ( el ) {
							el.classList.add( "jsmap-disabled" );
							el.style.cursor = "not-allowed";
							if ( el.nodeName.toLowerCase() !== "text" ) {
								el.setAttribute( "fill", opt.disabled.color );
							}
						})
					}

					// 反选
					if ( opt.disabled.except ) {
						var result = [];
						disabledName.forEach(function ( v ) {

							// 支持汉字和全拼两种形式
							if ( !v.match( /[a-z]/ ) ) {
								$( $path ).forEach(function ( path ) {
									if ( path.getAttribute( "data-name" ) === v ) {
										result.push( path.getAttribute( "data-id" ) );
									}
								})
							} else {
								result.push( v );
							}
						})
						
						// 克隆一个含有地区名称的数组
						var cloneNames = areaBox.map(function ( name ) {
							return name;
						});

						// 进行反选的筛选操作
						result.forEach(function ( v ) {
							cloneNames.splice( cloneNames.indexOf( v ), 1 );
						})

						// 设置禁用效果
						cloneNames.forEach(function ( v ) {
							setDisabled( $( ".jsmap-" + v, $el ) );
						})
					} else {
						disabledName.forEach(function ( v ) {

							// 设置禁用效果
							setDisabled( $( ".jsmap-" + v, $el ) );
						})
					}
				}

				// 悬浮提示层
				if ( opt.tip ) {
					if ( !$( "#jsmap-tip-layer" )[ 0 ] ) {
						document.body.insertAdjacentHTML("beforeend", '<div \
							id="jsmap-tip-layer" \
							style="\
								position:absolute;\
								top:0;\
								left:0;\
								z-index:999;\
								display:inline-block;\
								width:auto;\
								height:auto;\
								overflow:hidden;\
								display:none;\
							"\
						></div>' );
						$tip = $( "#jsmap-tip-layer" )[ 0 ];
					}
				}

				// 事件集合
				areaBox.forEach(function ( v ) {
					$( ".jsmap-" + v, $el ).forEach(function ( elem ) {
						elem.addEventListener("mouseenter", function () {
							if ( IsMobile || !opt.defaultInteractive ) {
								return;
							}

							var _this = this; 

							// 如果此区域被禁用
							// 则无任何事件
							if ( _this.classList.contains( "jsmap-disabled" ) ) {
								return;
							}

							// 鼠标悬浮时的填充色 ( 未被点击过的情况下 )
							if ( !_this.classList.contains( "jsmap-clicked" ) ) {

								// 克隆一个含有地区名称的数组
								var cloneAllName = areaBox.map(function ( name ) {
									return name;
								});

								if ( Methods.type( opt.fill.hoverColor ) === "string" ) {
									if ( _this.nodeName.toLowerCase() === "path" ) {
										_this.setAttribute( "fill", opt.fill.hoverColor );
									}
								}

								if ( Methods.type( opt.fill.hoverColor ) === "object" && !Methods.isEmptyObject( opt.fill.hoverColor ) ) {
									for ( var i in opt.fill.hoverColor ) {
										var v = opt.fill.hoverColor[ i ];
										if ( _this.getAttribute( "class" ).indexOf( "jsmap-" + i ) > -1 ) {
											_this.setAttribute( "fill", v );
										}
										cloneAllName.splice( cloneAllName.indexOf( i ), 1 );
									}

									// 未特殊设置的地区仍保持默认配置色
									cloneAllName.forEach(function ( area ) {
										if ( _this.getAttribute( "class" ).indexOf( "jsmap-" + area ) > -1 ) {
											_this.setAttribute( "fill", defaults.fill.hoverColor );
										}
									})
								}
							}

							// 悬浮回调事件
							opt.hoverCallback( _this.getAttribute( "data-id" ), _this.getAttribute( "data-name" ) );

							// 悬浮提示框
							if ( opt.tip ) {

								// 为 true 时显示地区的汉字名称
								if ( opt.tip === true ) {
									$tip.innerHTML = '<div \
										style="\
											padding:10px 12px;\
											color:#fff;\
											font-size:14px;\
											text-align:center;\
											border-radius:4px;\
											border:#777 solid 1px;\
											background:rgba(0,0,0,.75);"\
									>' + _this.getAttribute( "data-name" ) + '</div>';
								}

								// 是函数时可显示自定义内容
								// 函数的参数包含了地区的全拼和汉语名称
								if ( Methods.type( opt.tip ) === "function" ) {
									$tip.innerHTML = opt.tip( _this.getAttribute( "data-id" ), _this.getAttribute( "data-name" ) );
								}

								// 悬浮移动事件
								document.onmousemove = function ( event ) {
									var x = event.pageX + 12 + "px",
										y = event.pageY + 12 + "px";
									Methods.setCSS($tip, {
										transform: "translate3d(" + x + ", " + y + ", 0)",
										display: "block"
									});
								}
							}
						})
						elem.addEventListener("mouseleave", function () {
							if ( IsMobile || !opt.defaultInteractive ) {
								return;
							}

							// 如果此区域被禁用
							// 则无任何事件
							if ( this.classList.contains( "jsmap-disabled" ) ) {
								return;
							}

							// 恢复原始填充色 ( 未被点击过的情况下 )
							if ( !this.classList.contains( "jsmap-clicked" ) ) {
								if ( this.nodeName.toLowerCase() === "path" ) {
									this.setAttribute( "fill", this.getAttribute( "data-fill" ) );
								}
							}

							// 悬浮框内容清空并恢复位置
							if ( opt.tip ) {
								$tip.innerHTML = "";
								Methods.setCSS($tip, {
									transform: "translate3d(0, 0, 0)",
									display: "none"
								});
								document.onmousemove = null;
							}
						})
						elem.addEventListener("click", function () {
							var _this = this;

							// 如果此区域被禁用
							// 则无任何事件
							if ( _this.classList.contains( "jsmap-disabled" ) || !opt.defaultInteractive ) {
								return;
							}

							var id = _this.getAttribute( "data-id" );

							// 点击后的填充色
							if ( opt.fill.clickColor === false ) {
								return;
							} else {

								// 克隆一个含有地区名称的数组
								var cloneAllName = areaBox.map(function ( name ) {
									return name;
								});

								if ( typeof opt.fill.clickColor === "string" ) {
									if ( _this.nodeName.toLowerCase() === "path" ) {
										_this.setAttribute( "fill", opt.fill.clickColor );
									}
								}
								if ( Methods.type( opt.fill.clickColor ) === "object" && !Methods.isEmptyObject( opt.fill.clickColor ) ) {
									for ( var i in opt.fill.clickColor ) {
										var v = opt.fill.clickColor[ i ];
										if ( _this.getAttribute( "class" ).indexOf( "jsmap-" + i ) > -1 ) {
											_this.setAttribute( "fill", v );
										}
										cloneAllName.splice( cloneAllName.indexOf( i ), 1 );
									}

									// 未特殊设置的地区仍保持默认配置色
									cloneAllName.forEach(function ( area ) {
										if ( _this.getAttribute( "class" ).indexOf( "jsmap-" + area ) > -1 ) {
											_this.setAttribute( "fill", defaults.fill.clickColor );
										}
									})
								}
							}

							// 点击后的文字颜色
							if ( opt.areaName.clickColor !== false ) {
								if ( opt.areaName.show ) {
									var $elem = $( "text.jsmap-" + _this.getAttribute( "data-id" ), $el )[ 0 ];
									$elem.setAttribute( "fill", opt.areaName.clickColor );
									if ( !opt.multiple ) {
										$( $( "text", $el ) ).forEach(function ( text ) {
											if ( text !== $elem ) {
												text.setAttribute( "fill", opt.areaName.basicColor );
											}
										})
									}
								}
							}

							// 单选
							if ( !opt.multiple ) {
								_this.classList.add( "jsmap-clicked" );
								$( $pathText ).forEach(function ( pathText ) {
									if ( !pathText.classList.contains( "jsmap-" + id ) && !pathText.classList.contains( "jsmap-disabled" ) ) {
										pathText.classList.remove( "jsmap-clicked" );
										if ( pathText.nodeName.toLowerCase() !== "text" ) {
											pathText.setAttribute( "fill", pathText.getAttribute( "data-fill" ) );
										}
									}
								})
							} else {

								// 多选
								_this.classList[ $el.outerSelected ? "add" : "toggle" ]( "jsmap-clicked" );
								if ( !_this.classList.contains( "jsmap-clicked" ) ) {
									_this.setAttribute( "fill", _this.getAttribute( "data-fill" ) );
								}
							}

							// 点击回调事件
							// 函数的参数包含了地区的全拼和汉语名称
							if ( !$el.outerSelected && !$el.autoSelected ) {
								opt.clickCallback( _this.getAttribute( "data-id" ), _this.getAttribute( "data-name" ) );
							}
						})
					})
				})
	
				// 默认选中
				if ( Array.isArray( opt.selected ) && opt.selected.length ) {
					$el.autoSelected = true;
					opt.selected.forEach(function ( v ) {
						var $target = $( 'path[data-id="' + v + '"], path[data-name="' + v + '"]', $svg )[ 0 ];
						var evt = document.createEvent( "MouseEvents" );
						evt.initEvent( "click", true, true );
						$target && $target.dispatchEvent( evt );
					})
					$el.autoSelected = null;
				}

				// 缩放拖拽
				if ( opt.zoom.disabled === false ) {
					if ( opt.zoom.zoomRange ) {
						$el.insertAdjacentHTML("beforeend", '\
							<div class="jsmap-zoom-range">\
							<style>.jsmap-zoom-range{position:absolute;right:10px;top:10px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;}.jsmap-zoom-range div{width:20px;height:20px;background:#333;color:#fff;font-size:20px;cursor:pointer;border-radius:4px;text-align:center;line-height:20px;transition:background .2s;}.jsmap-zoom-range div:last-child{margin-top:5px;font-size:16px;}.jsmap-zoom-range div:hover{background:#555;}</style>\
								<div>+</div>\
								<div>−</div>\
							</div>\
						');
					}
					var $zoomIn = $( ".jsmap-zoom-range div:first-of-type", $el )[ 0 ],
						$zoomOut = $( ".jsmap-zoom-range div:last-of-type", $el )[ 0 ];

					var zoom = 1;
					Methods.setCSS($el, {
						overflow: "hidden"
					});

					if ( $zoomIn ) {
						$zoomIn.onclick = function () {
							wheelHandle( {}, -1 );
						}
					}
					if ( $zoomOut ) {
						$zoomOut.onclick = function () {
							wheelHandle( {}, 1 );
						}
					}
					
					function wheelHandle ( event, upDown ) {
						event.preventDefault && event.preventDefault();
						var Y = upDown || event.deltaY;
						if ( Y < 0 && zoom < parseFloat( opt.zoom.max ) ) {
							zoom += .2;
						} 
						if ( Y > 0 && zoom > 1 ) {
							zoom -= .2;
						}
						zoom = parseFloat( zoom.toFixed( 1 ) );
						Methods.setCSS($container, {
							transform: "scale(" + zoom + ")"
						});
						
						if ( zoom > 1 ) {
							var left = parseFloat( Methods.getCSS( $container, "margin-left" ) ),
								top = parseFloat( Methods.getCSS( $container, "margin-top" ) );
							var X = ( _width * zoom - _width ) / 2,
								Y = ( _height * zoom - _height ) / 2;
							if ( left > X ) {
								Methods.setCSS($container, {
									marginLeft: X + "px"
								});
							}
							if ( top > Y ) {
								Methods.setCSS($container, {
									marginTop: Y + "px"
								});
							}
							if ( left < -X ) {
								Methods.setCSS($container, {
									marginLeft: -X + "px"
								});
							}
							if ( top < -Y ) {
								Methods.setCSS($container, {
									marginTop: -Y + "px"
								});
							}
						}
						if ( Math.ceil( zoom ) === 1 ) {
							Methods.setCSS($container, {
								marginTop: 0,
								marginLeft: 0
							});
						}
					}
					if ( opt.zoom.wheelZoom ) {
						$el.addEventListener( "wheel", wheelHandle );
					}

					var _width = parseFloat( opt.width.indexOf( "%" ) > -1 ? Methods.getCSS( $container, "width" ) : opt.width ), 
						_height = parseFloat( height ),
						x, 
						y, 
						_x, 
						_y;
					function mousemoveHandle ( event ) {
						var _x = event.pageX - x,
							_y = event.pageY - y;
						if ( zoom > 1 ) {
							var X = ( _width * zoom - _width ) / 2,
								Y = ( _height * zoom - _height ) / 2;
							( _x > X ) && ( _x = X );
							( _y > Y ) && ( _y = Y );
							( _x < -X ) && ( _x = -X );
							( _y < -Y ) && ( _y = -Y );
							Methods.setCSS($container, {
								marginLeft: _x + "px",
								marginTop: _y + "px",
								transition: "0s",
								cursor: "move"
							});
							Methods.setCSS($svg, {
								pointerEvents: "none"
							});
						}
					}
					function mousedownHandle ( event ) {
						x = event.pageX - $container.offsetLeft,
						y = event.pageY - $container.offsetTop;
						document.addEventListener( "mousemove", mousemoveHandle );
					}
					function mouseupHandle () {
						document.removeEventListener( "mousemove", mousemoveHandle );
						$container.style.removeProperty( "cursor" );
						$svg.style.removeProperty( "pointer-events" );
					}
					$container.addEventListener( "mousedown", mousedownHandle );
					document.addEventListener( "mouseup", mouseupHandle );
				}

				// 标注点功能
				if ( opt.marker.disabled === false ) {
					$text.forEach(function ( text ) {
						var box = text.getBBox();

						// 必须使用正确的命名空间来创建 SVG $ 元素
						var image = document.createElementNS( "http://www.w3.org/2000/svg", "image" );
						Methods.setAttr(image, {
							x: parseInt( text.getAttribute( "x" ) ) + ( parseInt( box.width ) - 24 ) / 2,
							y: parseInt( text.getAttribute( "y" ) ) - ( opt.areaName.show ? opt.areaName.size : 0 ) - 22,
							width: 24,
							height: 24,
							style: "cursor:pointer",
							"data-id": text.getAttribute( "data-id" ),
							"data-name": text.getAttribute( "data-name" )
						});
						image.href.baseVal = opt.marker.icon;
						$svg.appendChild( image );

						$( "image", $svg ).forEach(function ( img ) {
							var id = img.getAttribute( "data-id" );

							// 判断是否为被禁用地区
							if ( $( 'path[data-id="' + id + '"]', $svg )[ 0 ].classList.contains( "jsmap-disabled" ) ) {
								img.setAttribute( "style", "cursor:not-allowed" );
								img.classList.add( "jsmap-disabled" );
								return;
							}
						})
					})
					$svg.addEventListener("click", function ( event ) {
						var target = event.target
						if ( target.nodeName.match( /(image|text)/ ) ) {
							if ( target.classList.contains( "jsmap-disabled" ) ) {
								return;
							}
							if ( Methods.type( opt.marker.click ) === "function" ) {
								opt.marker.click( target.getAttribute( "data-id" ), target.getAttribute( "data-name" ) );
							}
						}
					})
				}

				// 不显示区域名称
				if ( !opt.areaName.show ) { 
					$text.forEach(function ( text ) {
						$svg.removeChild( text );
					})
				}
			})
		},
		refresh: function ( selector ) {
			$( selector ).forEach(function ( $el ) {
				var cache_1 = $el[ "__jsmap_jsondata__" ],
					cache_2 = $el[ "__jsmap_options__" ];
				if ( cache_1 && cache_2 ) {
					jsMap.config( $el, cache_1, cache_2 );
				}
			})
		},
		remove: function ( selector ) {
			$( selector ).forEach(function ( $el ) {
				var getCssCache = $el[ "__jsmap_csscache__" ];
				if ( getCssCache ) {
					for ( var i in getCssCache ) {
						var v = getCssCache[ i ];
						if ( v ) {
							$el.style[ i ] = v;
						} else {
							$el.style.removeProperty( i );
						}
					}
				}
				$el.classList.remove( "jsmap-container" );
				$el[ "__jsmap_csscache__" ] = null;
				$el[ "__jsmap_jsondata__" ] = null;
				$el[ "__jsmap_options__" ] = null;
				$el.innerHTML = "";
			})
		},
		triggerClick: function ( selector, area ) {
			$( selector ).forEach(function ( $el ) {
				var target = $( area.match( /[a-z]/ ) ? ".jsmap-" + area : 'path[data-name="' + area + '"]', $el )[ 0 ];
				var evt = document.createEvent( "MouseEvents" );
				evt.initEvent( "click", true, true );
				target && target.dispatchEvent( evt );
			})
		},
		selected: function ( selector, area ) {
			var target = [];
			var $selector = $( selector );
			$selector.forEach(function ( $el ) {
				$el.outerSelected = true;
				if ( Array.isArray( area ) ) {
					area.forEach(function ( a ) {
						target.push( $( a.match( /[a-z]/ ) ? ".jsmap-" + a : 'path[data-name="' + a + '"]', $el )[ 0 ] );
					})
				}
				if ( typeof area === "string" ) {
					target.push( $( area.match( /[a-z]/ ) ? ".jsmap-" + area : 'path[data-name="' + area + '"]', $el )[ 0 ] );
				}
			})
			target.forEach(function ( el ) {
				var evt = document.createEvent( "MouseEvents" );
				evt.initEvent( "click", true, true );
				el && el.dispatchEvent( evt );
			})
			$selector.forEach(function ( $el ) {
				$el.outerSelected = null;
			})
		},
		getSelected: function ( selector, options ) {
			var $target = $( ".jsmap-svg-container", $( selector )[ 0 ] )[ 0 ];
			
			// 被选中的区域
			var $clicked = $( ".jsmap-clicked", $target );

			// 默认以数组形式返回
			if ( !options ) {
				options = {
					type: "array"
				};
			}
			if ( options ) {
				if ( options.type === "array" ) {
					var a = [], 
						b = [];
					if ( !$clicked.length ) {
						return [ [], [] ];
					}
					$( $clicked ).forEach(function ( el ) {
						a.push( el.getAttribute( "data-id" ) );
						b.push( el.getAttribute( "data-name" ) );
					})
					return [ a, b ];
				} 
				if ( options.type === "object" ) {
					var obj = {};
					if ( !$clicked.length ) {
						return obj;
					}
					$( $clicked ).forEach(function ( el ) {
						obj[ el.getAttribute( "data-id" ) ] = el.getAttribute( "data-name" );
					})
					return obj;
				}
			}
		},
		getJSON: function ( url, callback ) {
			if ( !url ) {
				return;
			}
			Request(url, function ( json ) {
				if ( Methods.type( callback ) === "function" ) {
					callback( json );
				}
			})
		},
		preloadJSON: function ( obj, callback ) {
			if ( Methods.type( obj ) === "object" && !Methods.isEmptyObject( obj ) ) {
				var cache = {};
				var totalSize = Object.keys( obj ).length;
				var successSize = 0;
				for ( var name in obj ) { 
					var url = obj[ name ];
					(function ( name, url ) {
						Request(url, function ( json ) {
							CacheJSON[ name ] = json;
							successSize++;
							if ( successSize === totalSize ) {
								if ( Methods.type( callback ) === "function" ) {
									callback( CacheJSON );
								}
							}
						})
					})( name, url );
				}
			}
		},
		getPreloadJSON: function ( name ) {
			return !name ? CacheJSON : CacheJSON[ name ];
		}
	};

	!(function freezeJsMap ( obj ) {
        Object.freeze( obj );
        Object.keys( obj ).forEach(function ( v ) {
            if ( typeof obj[ v ] === "object" ) {
                freezeJsMap( obj[ v ] );
            }
        })
    })( jsMap );

	return jsMap;

});