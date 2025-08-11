(function ($) {
    "use strict";

    $(document).ready(function () {
        var tagsContainer = $(".tags-container");
        if (tagsContainer.length) {
            function initSimulation() {
                var e = Matter.Engine,
                    t = Matter.Render,
                    r = Matter.Events,
                    s = Matter.MouseConstraint,
                    a = Matter.Mouse,
                    n = Matter.World,
                    c = Matter.Bodies,
                    l = e.create(),
                    m = l.world,
                    g = document.querySelector(".tags-container"),
                    u = g.clientWidth,
                    d = g.clientHeight,
                    o = t.create({
                        element: g,
                        engine: l,
                        options: {
                            width: u,
                            height: d,
                            pixelRatio: 2,
                            background: "transparent",
                            wireframes: !1
                        }
                    });

                // Load images and create bodies
                function loadImage(url, callback) {
                    var img = new Image();
                    img.onload = function () {
                        callback(img);
                    };
                    img.src = url;
                }

                // Create a rectangle with an image texture
                function createRectangle(x, y, width, height, textureUrl) {
                    loadImage(textureUrl, function (loadedImage) {
                        var rect = c.rectangle(x, y, width, height, {
                            chamfer: {
                                radius: 20
                            },
                            render: {
                                sprite: {
                                    texture: loadedImage.src,
                                    xScale: 1,
                                    yScale: 1
                                }
                            }
                        });
                        n.add(m, rect);
                    });
                }

                // Create the ground and walls
                var h = c.rectangle(u / 2 + 160, d + 80, u + 320, 160, {
                    render: {
                        fillStyle: "#fff"
                    },
                    isStatic: !0
                });
                var S = c.rectangle(-80, d / 2, 160, d, { isStatic: !0 });
                var p = c.rectangle(u + 80, d / 2, 160, 1200, { isStatic: !0 });
                var v = c.rectangle(u / 2 + 160, -80, u + 320, 160, { isStatic: !0 });

                // Add walls to the world
                n.add(m, [h, S, p, v]);

                // Create rectangles with textures
                createRectangle(u / 2 + 150, 200, 164, 56, "<?php echo get_template_directory_uri(); ?>/assets/img/shape/elements_1_1.svg");
                createRectangle(u / 2 - 150, 160, 122, 56, "<?php echo get_template_directory_uri(); ?>/assets/img/shape/elements_1_2.svg");
                createRectangle(u / 2 + 250, 120, 104, 56, "<?php echo get_template_directory_uri(); ?>/assets/img/shape/elements_1_3.svg");
                // Add more rectangles as needed...

                // Mouse control
                var mouse = a.create(o.canvas),
                    mouseConstraint = s.create(l, {
                        mouse: mouse,
                        constraint: {
                            stiffness: .2,
                            render: {
                                visible: !1
                            }
                        }
                    });

                n.add(m, mouseConstraint);
                o.mouse = mouse;
                mouse.element.removeEventListener("mousewheel", mouse.mousewheel);
                mouse.element.removeEventListener("DOMMouseScroll", mouse.mousewheel);

                // Run the engine
                e.run(l);
                t.run(o);
            }

            // Initialize simulation when the container is visible
            var observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        initSimulation();
                        observer.disconnect();
                    }
                });
            }, {});

            observer.observe(tagsContainer[0]);
        }
    });
})(jQuery);
