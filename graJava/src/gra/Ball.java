package gra;

import java.awt.geom.Ellipse2D;

public class Ball extends Ellipse2D.Float {

    private static final long serialVersionUID = 1L;
    public int lvl;
    public int move;
    public int xMove;

    public Ball(float x, float y, float w, float h, int lvl)
    {
        super(x, y, w, h);
        this.lvl = lvl;
        move = 0;
        xMove = 0;
    }

    public void jump()
    {
        move = 30 * lvl;
    }
}