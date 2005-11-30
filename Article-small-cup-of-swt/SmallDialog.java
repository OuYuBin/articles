import org.eclipse.swt.*;
import org.eclipse.swt.widgets.*;
import org.eclipse.swt.graphics.*;

public class SmallDialog {

public static void main(String[] args) {
	Display display = new Display();
	Shell shell = new Shell(display);
	shell.setText("Main");
	Menu mb = new Menu(shell, SWT.BAR);
	shell.setMenuBar(mb);

	Shell dialog = new Shell(shell, SWT.CLOSE);
	dialog.setText(shell.getText());
	Rectangle bounds = display.getClientArea();
	bounds.x += 5; bounds.width -= 10;
	bounds.y += 5; bounds.height /= 2;
	dialog.setBounds(bounds);

	shell.open();
	dialog.open();
	
	while (!shell.isDisposed()) {
		if (!display.readAndDispatch())
			display.sleep();
	}
	display.dispose();
}
}